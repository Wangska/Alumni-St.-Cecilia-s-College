<?php
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';
require_once __DIR__ . '/../inc/logger.php';

header('Content-Type: application/json');

if (!current_user()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

$user = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['testimonial_id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$testimonialId = (int)$_POST['testimonial_id'];
$pdo = get_pdo();

try {
    // Fetch testimonial and ensure ownership
    $stmt = $pdo->prepare('SELECT id, user_id, graduation_photo FROM testimonials WHERE id = ?');
    $stmt->execute([$testimonialId]);
    $t = $stmt->fetch();
    
    if (!$t) {
        echo json_encode(['success' => false, 'message' => 'Testimonial not found']);
        exit;
    }
    if ((int)$t['user_id'] !== (int)$user['id']) {
        echo json_encode(['success' => false, 'message' => 'You can only delete your own testimonial']);
        exit;
    }
    
    // Delete image if present
    if (!empty($t['graduation_photo'])) {
        $imgPath = __DIR__ . '/../' . $t['graduation_photo'];
        if (is_file($imgPath)) { @unlink($imgPath); }
    }
    
    // Delete testimonial
    $stmt = $pdo->prepare('DELETE FROM testimonials WHERE id = ?');
    $stmt->execute([$testimonialId]);
    
    ActivityLogger::logDelete('Testimonial', 'Alumni deleted testimonial', [
        'testimonial_id' => $testimonialId,
        'user_id' => (int)$user['id']
    ]);
    
    echo json_encode(['success' => true, 'message' => 'Testimonial deleted successfully.']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to delete testimonial.']);
}
?>


