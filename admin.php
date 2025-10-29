<?php
require_once __DIR__ . '/bootstrap.php';

use App\Controllers\AdminController;
use App\Models\Course;
use App\Models\Alumni;
use App\Models\Gallery;
use App\Models\User;
use App\Models\Career;
use App\Models\ForumTopic;

// Check if user is logged in and is admin
if (!isset($_SESSION['user'])) {
    header('Location: /scratch/');
    exit;
}

if (($_SESSION['user']['type'] ?? 0) != 1) {
    header('Location: /scratch/dashboard.php');
    exit;
}

// Include logger
require_once __DIR__ . '/inc/logger.php';

// Get the requested page
$page = $_GET['page'] ?? 'dashboard';
$action = $_GET['action'] ?? '';

// Handle user approval
if ($page === 'users' && $action === 'approve' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $alumniModel = new Alumni();
    
    // Verify CSRF token
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
        die('Invalid CSRF token');
    }
    
    $alumniId = (int)$_POST['alumni_id'];
    $alumniModel->update($alumniId, ['status' => 1]);
    
    $_SESSION['success'] = 'Alumni approved successfully!';
    header('Location: /scratch/admin.php?page=users');
    exit;
}

// Handle document fetching for users
if ($page === 'users' && $action === 'get_documents' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $alumnusId = (int)($_GET['alumni_id'] ?? 0);
    if ($alumnusId > 0) {
        try {
            $pdo = get_pdo();
            $stmt = $pdo->prepare('SELECT * FROM alumni_documents WHERE alumnus_id = ? ORDER BY upload_date DESC');
            $stmt->execute([$alumnusId]);
            $documents = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Clear any output and send JSON
            ob_clean();
            header('Content-Type: application/json');
            echo json_encode(['documents' => $documents]);
            exit;
        } catch (Exception $e) {
            ob_clean();
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
            exit;
        }
    } else {
        ob_clean();
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid alumni ID']);
        exit;
    }
}

// Debug: Log all requests to admin.php
if (isset($_GET['page']) && $_GET['page'] === 'users' && isset($_GET['action']) && $_GET['action'] === 'get_documents') {
    error_log("Admin.php: Document fetch request for alumni_id: " . ($_GET['alumni_id'] ?? 'not set'));
}

// Handle gallery CRUD operations
if ($page === 'galleries' && !empty($action) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $galleryModel = new Gallery();
    
    // Verify CSRF token
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
        die('Invalid CSRF token');
    }
    
    switch ($action) {
        case 'upload':
            // Handle multiple image uploads with descriptions
            $uploadedCount = 0;
            if (!empty($_FILES['images']['name'][0])) {
                $destDir = __DIR__ . '/uploads';
                if (!is_dir($destDir)) {
                    @mkdir($destDir, 0775, true);
                }
                
                $descriptions = $_POST['descriptions'] ?? [];
                
                foreach ($_FILES['images']['name'] as $key => $filename) {
                    if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
                        $tmpName = $_FILES['images']['tmp_name'][$key];
                        $ext = pathinfo($filename, PATHINFO_EXTENSION);
                        $safeName = 'gallery_' . bin2hex(random_bytes(6)) . ($ext ? '.' . $ext : '');
                        $dest = $destDir . '/' . $safeName;
                        
                        if (move_uploaded_file($tmpName, $dest)) {
                            $description = trim($descriptions[$key] ?? '');
                            $galleryModel->create([
                                'image_path' => $safeName,  // Store filename
                                'about' => $description,     // Store description/caption
                            ]);
                            // Log the upload
                            \ActivityLogger::logCreate('Gallery', $filename);
                            $uploadedCount++;
                        }
                    }
                }
            }
            
            if ($uploadedCount > 0) {
                $_SESSION['success'] = $uploadedCount . ' image(s) uploaded successfully!';
            } else {
                $_SESSION['error'] = 'No images were uploaded.';
            }
            header('Location: /scratch/admin.php?page=galleries');
            exit;
            break;
        
        case 'add':
            // Handle image upload
            $imageFile = '';
            if (!empty($_FILES['image']['name']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $safeName = 'gallery_' . bin2hex(random_bytes(6)) . ($ext ? '.' . $ext : '');
                $destDir = __DIR__ . '/uploads';
                if (!is_dir($destDir)) {
                    @mkdir($destDir, 0775, true);
                }
                $dest = $destDir . '/' . $safeName;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                    $imageFile = $safeName;
                }
            }
            
            if ($imageFile) {
                $galleryModel->create([
                    'about' => $imageFile,  // 'about' column stores the filename
                ]);
                \ActivityLogger::logCreate('Gallery', $imageFile);
                $_SESSION['success'] = 'Image added to gallery successfully!';
            } else {
                $_SESSION['error'] = 'Failed to upload image!';
            }
            break;
            
        case 'delete':
            $id = (int)$_POST['id'];
            $imageFile = $_POST['image_file'] ?? '';
            
            // Delete from database
            $galleryModel->delete($id);
            
            // Delete image file
            if ($imageFile && file_exists(__DIR__ . '/uploads/' . $imageFile)) {
                @unlink(__DIR__ . '/uploads/' . $imageFile);
            }
            
            // Log the deletion
            \ActivityLogger::logDelete('Gallery', $imageFile);
            
            $_SESSION['deleted'] = 'Image deleted successfully!';
            break;
    }
    
    header('Location: /scratch/admin.php?page=galleries');
    exit;
}

// Handle settings actions
if ($page === 'settings' && !empty($action)) {
    // Verify CSRF token for POST requests
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
        die('Invalid CSRF token');
    }
    
    switch ($action) {
        case 'update_account':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $userModel = new User();
                $userId = $_SESSION['user']['id'];
                
                $userModel->update($userId, [
                    'name' => trim($_POST['name'] ?? ''),
                    'username' => trim($_POST['username'] ?? ''),
                ]);
                
                // Update session
                $_SESSION['user']['name'] = trim($_POST['name'] ?? '');
                $_SESSION['user']['username'] = trim($_POST['username'] ?? '');
                
                $_SESSION['success'] = 'Account updated successfully!';
                header('Location: /scratch/admin.php?page=settings');
                exit;
            }
            break;
            
        case 'change_password':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $userModel = new User();
                $userId = $_SESSION['user']['id'];
                $user = $userModel->findById($userId);
                
                $currentPassword = $_POST['current_password'] ?? '';
                $newPassword = $_POST['new_password'] ?? '';
                $confirmPassword = $_POST['confirm_password'] ?? '';
                
                // Verify current password
                if (!$userModel->verifyPassword($currentPassword, $user['password'])) {
                    $_SESSION['error'] = 'Current password is incorrect!';
                    header('Location: /scratch/admin.php?page=settings');
                    exit;
                }
                
                // Check if passwords match
                if ($newPassword !== $confirmPassword) {
                    $_SESSION['error'] = 'New passwords do not match!';
                    header('Location: /scratch/admin.php?page=settings');
                    exit;
                }
                
                // Update password
                $userModel->update($userId, [
                    'password' => md5($newPassword),
                ]);
                
                $_SESSION['success'] = 'Password changed successfully!';
                header('Location: /scratch/admin.php?page=settings');
                exit;
            }
            break;
            
        case 'backup_db':
            // Database backup (download SQL)
            $pdo = get_pdo();
            $tables = [];
            
            // Get all tables
            $result = $pdo->query('SHOW TABLES');
            while ($row = $result->fetch(PDO::FETCH_NUM)) {
                $tables[] = $row[0];
            }
            
            $output = "-- Database Backup\n";
            $output .= "-- Generated: " . date('Y-m-d H:i:s') . "\n\n";
            
            foreach ($tables as $table) {
                $output .= "-- Table: $table\n";
                $output .= "DROP TABLE IF EXISTS `$table`;\n";
                
                // Get CREATE TABLE
                $createTable = $pdo->query("SHOW CREATE TABLE `$table`")->fetch();
                $output .= $createTable[1] . ";\n\n";
                
                // Get data
                $rows = $pdo->query("SELECT * FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($rows)) {
                    foreach ($rows as $row) {
                        $values = array_map(function($val) use ($pdo) {
                            return $val === null ? 'NULL' : $pdo->quote($val);
                        }, array_values($row));
                        $output .= "INSERT INTO `$table` VALUES (" . implode(', ', $values) . ");\n";
                    }
                    $output .= "\n";
                }
            }
            
            // Download file
            header('Content-Type: application/sql');
            header('Content-Disposition: attachment; filename="sccalumni_backup_' . date('Y-m-d_His') . '.sql"');
            echo $output;
            exit;
            break;
    }
}

// Handle careers CRUD operations
if ($page === 'careers' && !empty($action) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $careerModel = new Career();
    
    // Verify CSRF token
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
        die('Invalid CSRF token');
    }
    
    switch ($action) {
        case 'add':
            $jobTitle = trim($_POST['job_title'] ?? '');
            
            // Handle company logo upload
            $companyLogo = '';
            if (!empty($_FILES['company_logo']['name']) && is_uploaded_file($_FILES['company_logo']['tmp_name'])) {
                $ext = pathinfo($_FILES['company_logo']['name'], PATHINFO_EXTENSION);
                $safeName = 'company_' . bin2hex(random_bytes(6)) . ($ext ? '.' . $ext : '');
                $destDir = __DIR__ . '/uploads';
                if (!is_dir($destDir)) {
                    @mkdir($destDir, 0775, true);
                }
                $dest = $destDir . '/' . $safeName;
                if (move_uploaded_file($_FILES['company_logo']['tmp_name'], $dest)) {
                    $companyLogo = $safeName;
                }
            }
            
            $careerModel->create([
                'company' => trim($_POST['company'] ?? ''),
                'location' => trim($_POST['location'] ?? ''),
                'job_title' => $jobTitle,
                'description' => trim($_POST['description'] ?? ''),
                'company_logo' => $companyLogo,
                'user_id' => $_SESSION['user']['id'],
                'date_created' => date('Y-m-d H:i:s'),
            ]);
            \ActivityLogger::logCreate('Job Posting', $jobTitle);
            $_SESSION['success'] = 'Job posting added successfully!';
            break;
            
        case 'edit':
            $jobTitle = trim($_POST['job_title'] ?? '');
            
            // Handle company logo upload for edit
            $companyLogo = $_POST['existing_company_logo'] ?? '';
            if (!empty($_FILES['company_logo']['name']) && is_uploaded_file($_FILES['company_logo']['tmp_name'])) {
                $ext = pathinfo($_FILES['company_logo']['name'], PATHINFO_EXTENSION);
                $safeName = 'company_' . bin2hex(random_bytes(6)) . ($ext ? '.' . $ext : '');
                $destDir = __DIR__ . '/uploads';
                if (!is_dir($destDir)) {
                    @mkdir($destDir, 0775, true);
                }
                $dest = $destDir . '/' . $safeName;
                if (move_uploaded_file($_FILES['company_logo']['tmp_name'], $dest)) {
                    // Delete old logo if exists
                    if ($companyLogo && file_exists($destDir . '/' . $companyLogo)) {
                        @unlink($destDir . '/' . $companyLogo);
                    }
                    $companyLogo = $safeName;
                }
            }
            
            $careerModel->update((int)$_POST['id'], [
                'company' => trim($_POST['company'] ?? ''),
                'location' => trim($_POST['location'] ?? ''),
                'job_title' => $jobTitle,
                'description' => trim($_POST['description'] ?? ''),
                'company_logo' => $companyLogo,
            ]);
            \ActivityLogger::logUpdate('Job Posting', $jobTitle);
            $_SESSION['success'] = 'Job posting updated successfully!';
            break;
            
        case 'delete':
            $jobId = (int)$_POST['id'];
            $job = $careerModel->find($jobId);
            $jobTitle = $job['job_title'] ?? 'Unknown';
            $careerModel->delete($jobId);
            \ActivityLogger::logDelete('Job Posting', $jobTitle);
            $_SESSION['deleted'] = 'Job posting deleted successfully!';
            break;
    }
    
    header('Location: /scratch/admin.php?page=careers');
    exit;
}

// Handle job application actions
if ($page === 'job-applications' && !empty($action) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = \App\Core\Database::getInstance();
    $pdo = $db->getConnection();
    
    // Verify CSRF token
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
        die('Invalid CSRF token');
    }
    
    $applicationId = (int)($_POST['application_id'] ?? 0);
    
    if ($applicationId <= 0) {
        $_SESSION['error'] = 'Invalid application ID.';
        header('Location: /scratch/admin.php?page=job-applications');
        exit;
    }
    
    switch ($action) {
        case 'update_status':
            $newStatus = $_POST['status'] ?? '';
            $notes = trim($_POST['notes'] ?? '');
            
            if (!in_array($newStatus, ['pending', 'reviewed', 'accepted', 'rejected'])) {
                $_SESSION['error'] = 'Invalid status.';
                break;
            }
            
            try {
                $stmt = $pdo->prepare("UPDATE job_applications SET status = ?, notes = ? WHERE id = ?");
                $stmt->execute([$newStatus, $notes, $applicationId]);
                
                \ActivityLogger::logUpdate('Job Application', 'Status updated to: ' . $newStatus);
                $_SESSION['success'] = 'Application status updated successfully!';
            } catch (Exception $e) {
                $_SESSION['error'] = 'Failed to update application status.';
            }
            break;
            
        case 'delete':
            try {
                $stmt = $pdo->prepare("DELETE FROM job_applications WHERE id = ?");
                $stmt->execute([$applicationId]);
                
                \ActivityLogger::logDelete('Job Application', 'Application ID: ' . $applicationId);
                $_SESSION['success'] = 'Application deleted successfully!';
            } catch (Exception $e) {
                $_SESSION['error'] = 'Failed to delete application.';
            }
            break;
    }
    
    header('Location: /scratch/admin.php?page=job-applications');
    exit;
}

// Handle course CRUD operations
if ($page === 'courses' && !empty($action) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $courseModel = new Course();
    
    // Verify CSRF token
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
        die('Invalid CSRF token');
    }
    
    switch ($action) {
        case 'add':
            $courseName = trim($_POST['course'] ?? '');
            
            // Handle image upload
            $imageFile = '';
            if (!empty($_FILES['image']['name']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $safeName = 'course_' . bin2hex(random_bytes(6)) . ($ext ? '.' . $ext : '');
                $destDir = __DIR__ . '/uploads';
                if (!is_dir($destDir)) {
                    @mkdir($destDir, 0775, true);
                }
                $dest = $destDir . '/' . $safeName;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                    $imageFile = $safeName;
                }
            }
            
            $courseModel->create([
                'course' => $courseName,
                'about' => trim($_POST['about'] ?? ''),
                'image' => $imageFile,
            ]);
            \ActivityLogger::logCreate('Course', $courseName);
            $_SESSION['success'] = 'Course added successfully!';
            break;
            
        case 'edit':
            $courseName = trim($_POST['course'] ?? '');
            $courseId = (int)$_POST['id'];
            
            // Get current course data
            $currentCourse = $courseModel->find($courseId);
            
            // Handle image upload/removal
            $imageFile = $currentCourse['image'] ?? ''; // Keep current image by default
            
            // Check if user wants to remove current image
            if (!empty($_POST['remove_image']) && $_POST['remove_image'] == '1') {
                // Delete old image file
                if (!empty($imageFile)) {
                    $oldImagePath = __DIR__ . '/uploads/' . $imageFile;
                    if (file_exists($oldImagePath)) {
                        @unlink($oldImagePath);
                    }
                }
                $imageFile = ''; // Remove image
            }
            
            // Handle new image upload
            if (!empty($_FILES['image']['name']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
                // Delete old image if exists
                if (!empty($currentCourse['image'])) {
                    $oldImagePath = __DIR__ . '/uploads/' . $currentCourse['image'];
                    if (file_exists($oldImagePath)) {
                        @unlink($oldImagePath);
                    }
                }
                
                // Upload new image
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $safeName = 'course_' . bin2hex(random_bytes(6)) . ($ext ? '.' . $ext : '');
                $destDir = __DIR__ . '/uploads';
                if (!is_dir($destDir)) {
                    @mkdir($destDir, 0775, true);
                }
                $dest = $destDir . '/' . $safeName;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                    $imageFile = $safeName;
                }
            }
            
            $courseModel->update($courseId, [
                'course' => $courseName,
                'about' => trim($_POST['about'] ?? ''),
                'image' => $imageFile,
            ]);
            \ActivityLogger::logUpdate('Course', $courseName);
            $_SESSION['success'] = 'Course updated successfully!';
            break;
            
        case 'delete':
            $courseId = (int)$_POST['id'];
            $course = $courseModel->find($courseId);
            $courseName = $course['course'] ?? 'Unknown';
            
            // Delete associated image file
            if (!empty($course['image'])) {
                $imagePath = __DIR__ . '/uploads/' . $course['image'];
                if (file_exists($imagePath)) {
                    @unlink($imagePath);
                }
            }
            
            $courseModel->delete($courseId);
            \ActivityLogger::logDelete('Course', $courseName);
            $_SESSION['deleted'] = 'Course deleted successfully!';
            break;
    }
    
    header('Location: /scratch/admin.php?page=courses');
    exit;
}

// Handle forum CRUD operations
if ($page === 'forum' && !empty($action) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $forumTopicModel = new ForumTopic();
    
    // Verify CSRF token
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
        die('Invalid CSRF token');
    }
    
    switch ($action) {
        case 'add':
            $topicTitle = trim($_POST['title'] ?? '');
            $forumTopicModel->create([
                'title' => $topicTitle,
                'description' => trim($_POST['description'] ?? ''),
                'user_id' => $_SESSION['user']['id'],
            ]);
            \ActivityLogger::logCreate('Forum Topic', $topicTitle);
            $_SESSION['success'] = 'Forum topic created successfully!';
            break;
            
        case 'edit':
            $topicTitle = trim($_POST['title'] ?? '');
            $forumTopicModel->update((int)$_POST['id'], [
                'title' => $topicTitle,
                'description' => trim($_POST['description'] ?? ''),
            ]);
            \ActivityLogger::logUpdate('Forum Topic', $topicTitle);
            $_SESSION['success'] = 'Forum topic updated successfully!';
            break;
            
        case 'delete':
            $topicId = (int)$_POST['id'];
            $topic = $forumTopicModel->find($topicId);
            $topicTitle = $topic['title'] ?? 'Unknown';
            $forumTopicModel->delete($topicId);
            \ActivityLogger::logDelete('Forum Topic', $topicTitle);
            $_SESSION['deleted'] = 'Forum topic deleted successfully!';
            break;
    }
    
    header('Location: /scratch/admin.php?page=forum');
    exit;
}

// Initialize controller
$controller = new AdminController();

// Route to appropriate method
switch ($page) {
    case 'dashboard':
        $controller->dashboard();
        break;
    case 'alumni':
        $controller->alumni();
        break;
    case 'events':
        $controller->events();
        break;
    case 'event-participants':
        $controller->eventParticipants();
        break;
    case 'announcements':
        $controller->announcements();
        break;
    case 'courses':
        $controller->courses();
        break;
    case 'users':
        $controller->users();
        break;
    case 'galleries':
        $controller->galleries();
        break;
    case 'careers':
        $controller->careers();
        break;
    case 'job-applications':
        $controller->jobApplications();
        break;
    case 'forum':
        $controller->forum();
        break;
    case 'forum-view':
        $controller->forumTopic();
        break;
        case 'success-stories':
            $controller->successStories();
            break;
        case 'testimonials':
            $controller->testimonials();
            break;
    case 'settings':
        $controller->settings();
        break;
    case 'logs':
        require __DIR__ . '/views/admin/logs.php';
        break;
    default:
        $controller->dashboard();
        break;
}

