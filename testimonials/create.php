<?php
declare(strict_types=1);
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';
require_login();

$user = current_user();
$pdo = get_pdo();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quote = trim($_POST['quote'] ?? '');
    $authorName = trim($_POST['author_name'] ?? '');
    $graduationYear = (int)($_POST['graduation_year'] ?? 0);
    $course = trim($_POST['course'] ?? '');
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    // Validate CSRF token
    if (!hash_equals(csrf_token(), $csrf_token)) {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Invalid request. Please try again.'
        ]);
        exit;
    }
    
    // Validate required fields
    if (empty($quote) || empty($authorName) || empty($graduationYear) || empty($course)) {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'All fields are required.'
        ]);
        exit;
    }
    
    // Validate quote length
    if (strlen($quote) < 20 || strlen($quote) > 500) {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Quote must be between 20 and 500 characters.'
        ]);
        exit;
    }
    
    // Validate graduation year
    if ($graduationYear < 1990 || $graduationYear > date('Y')) {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Please enter a valid graduation year.'
        ]);
        exit;
    }
    
    // Handle file upload
    $imagePath = null;
    if (isset($_FILES['graduation_photo']) && $_FILES['graduation_photo']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['graduation_photo'];
        
        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!in_array($file['type'], $allowedTypes)) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Invalid file type. Please upload a JPEG, PNG, or GIF image.'
            ]);
            exit;
        }
        
        // Validate file size (5MB limit)
        if ($file['size'] > 5 * 1024 * 1024) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'File size too large. Maximum 5MB allowed.'
            ]);
            exit;
        }
        
        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'testimonial_' . time() . '_' . uniqid() . '.' . $extension;
        $uploadPath = __DIR__ . '/../uploads/testimonials/' . $filename;
        
        // Create uploads directory if it doesn't exist
        $uploadDir = dirname($uploadPath);
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            $imagePath = 'uploads/testimonials/' . $filename;
        } else {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Failed to upload image. Please try again.'
            ]);
            exit;
        }
    }
    
    // Ensure testimonials table exists (in case migration hasn't been run)
    try {
        $pdo->exec("CREATE TABLE IF NOT EXISTS `testimonials` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `user_id` int(11) NOT NULL,
            `quote` text NOT NULL,
            `author_name` varchar(255) NOT NULL,
            `graduation_year` int(4) NOT NULL,
            `course` varchar(255) NOT NULL,
            `graduation_photo` varchar(255) DEFAULT NULL,
            `status` tinyint(1) NOT NULL DEFAULT 0,
            `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `user_id` (`user_id`),
            KEY `status` (`status`),
            KEY `created` (`created`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");

        // Ensure columns exist if table pre-existed with older schema
        $pdo->exec("ALTER TABLE `testimonials` ADD COLUMN IF NOT EXISTS `author_name` varchar(255) NOT NULL AFTER `quote`;");
        $pdo->exec("ALTER TABLE `testimonials` ADD COLUMN IF NOT EXISTS `graduation_year` int(4) NOT NULL AFTER `author_name`;
                     ");
        $pdo->exec("ALTER TABLE `testimonials` ADD COLUMN IF NOT EXISTS `course` varchar(255) NOT NULL AFTER `graduation_year`;");
        $pdo->exec("ALTER TABLE `testimonials` ADD COLUMN IF NOT EXISTS `graduation_photo` varchar(255) NULL AFTER `course`;");
        $pdo->exec("ALTER TABLE `testimonials` ADD COLUMN IF NOT EXISTS `status` tinyint(1) NOT NULL DEFAULT 0 AFTER `graduation_photo`;");
        $pdo->exec("ALTER TABLE `testimonials` ADD COLUMN IF NOT EXISTS `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `status`;");
    } catch (Exception $e) {
        // proceed even if create fails; insert will surface a clearer error below
    }

    // Insert testimonial into database
    try {
        $stmt = $pdo->prepare('INSERT INTO testimonials (user_id, quote, author_name, graduation_year, course, graduation_photo, created, status) VALUES (?, ?, ?, ?, ?, ?, NOW(), 0)');
        $stmt->execute([$user['id'], $quote, $authorName, $graduationYear, $course, $imagePath]);
        
        // Return JSON response for AJAX
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'message' => 'Your testimonial has been submitted for review. It will be published once approved by the administrator.'
        ]);
        exit;
    } catch (Exception $e) {
        // Return JSON error response with helpful diagnostics in development
        header('Content-Type: application/json');
        $message = 'Failed to submit your testimonial. Please try again.';
        if (isset($_GET['debug']) || (defined('APP_ENV') && APP_ENV === 'local')) {
            $message .= ' Error: ' . $e->getMessage();
        }
        echo json_encode([
            'success' => false,
            'message' => $message
        ]);
        exit;
    }
} else {
    // If not POST request, redirect to testimonials page
    header('Location: index.php');
    exit;
}
?>
