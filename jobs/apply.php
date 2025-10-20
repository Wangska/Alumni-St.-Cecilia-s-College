<?php
declare(strict_types=1);
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';
require_once __DIR__ . '/../inc/logger.php';
require_login();

$user = current_user();
$pdo = get_pdo();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();
    
    $jobId = (int)($_POST['job_id'] ?? 0);
    $coverLetter = trim($_POST['cover_letter'] ?? '');
    
    // Debug: Log the received data
    error_log("Job Application Debug - Job ID: " . $jobId . ", Cover Letter Length: " . strlen($coverLetter));
    
    if ($jobId <= 0 || empty($coverLetter)) {
        $_SESSION['error'] = 'Please fill in all required fields. Job ID: ' . $jobId . ', Cover Letter: ' . (empty($coverLetter) ? 'Empty' : 'Present');
        header('Location: /scratch/jobs/');
        exit;
    }
    
    // Enforce one active application per job: block if last status is not 'rejected'
    $stmt = $pdo->prepare("SELECT status FROM job_applications WHERE job_id = ? AND user_id = ? ORDER BY applied_at DESC LIMIT 1");
    $stmt->execute([$jobId, $user['id']]);
    $last = $stmt->fetch();
    if ($last) {
        $lastStatus = strtolower((string)$last['status']);
        if ($lastStatus !== 'rejected') {
            $_SESSION['error'] = 'You have already applied for this job. Please wait for approval or rejection before applying again.';
            header('Location: /scratch/jobs/');
            exit;
        }
    }
    
    // Handle resume upload
    $resumeFile = '';
    if (!empty($_FILES['resume']['name']) && is_uploaded_file($_FILES['resume']['tmp_name'])) {
        // Validate file type
        $allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        $fileType = $_FILES['resume']['type'];
        
        if (!in_array($fileType, $allowedTypes)) {
            $_SESSION['error'] = 'Invalid file type. Please upload PDF, DOC, or DOCX files only.';
            header('Location: /scratch/jobs/');
            exit;
        }
        
        // Validate file size (5MB max)
        if ($_FILES['resume']['size'] > 5 * 1024 * 1024) {
            $_SESSION['error'] = 'File size too large. Maximum size is 5MB.';
            header('Location: /scratch/jobs/');
            exit;
        }
        
        $ext = pathinfo($_FILES['resume']['name'], PATHINFO_EXTENSION);
        $safeName = 'resume_' . bin2hex(random_bytes(6)) . ($ext ? '.' . $ext : '');
        $destDir = __DIR__ . '/../uploads';
        if (!is_dir($destDir)) {
            @mkdir($destDir, 0775, true);
        }
        $dest = $destDir . '/' . $safeName;
        if (move_uploaded_file($_FILES['resume']['tmp_name'], $dest)) {
            $resumeFile = $safeName;
        }
    }
    
    try {
        // Insert application
        $stmt = $pdo->prepare("INSERT INTO job_applications (job_id, user_id, cover_letter, resume_file, applied_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$jobId, $user['id'], $coverLetter, $resumeFile]);
        
        // Log the application
        ActivityLogger::logCreate('Job Application', 'Applied for job ID: ' . $jobId);
        
        $_SESSION['success'] = 'Your application has been submitted successfully! We will review it and get back to you soon.';
        $_SESSION['application_notification'] = [
            'type' => 'success',
            'title' => 'Application Sent Successfully!',
            'message' => 'Your application has been sent and is now under review. The review process typically takes 2-3 business days.',
            'icon' => 'fas fa-paper-plane',
            'duration' => 5000
        ];
        
        // Debug: Log successful application
        error_log("Job Application Success - User ID: " . $user['id'] . ", Job ID: " . $jobId);
    } catch (Exception $e) {
        $_SESSION['error'] = 'An error occurred while submitting your application. Please try again.';
    }
    
    header('Location: /scratch/jobs/');
    exit;
}

// If not POST request, redirect to jobs page
header('Location: /scratch/jobs/');
exit;
?>
