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

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['story_id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$storyId = (int)$_POST['story_id'];
$pdo = get_pdo();

try {
    // Fetch story and ensure ownership
    $stmt = $pdo->prepare('SELECT id, user_id, image FROM success_stories WHERE id = ?');
    $stmt->execute([$storyId]);
    $story = $stmt->fetch();
    
    if (!$story) {
        echo json_encode(['success' => false, 'message' => 'Story not found']);
        exit;
    }
    if ((int)$story['user_id'] !== (int)$user['id']) {
        echo json_encode(['success' => false, 'message' => 'You can only delete your own story']);
        exit;
    }
    
    // Delete image if present
    if (!empty($story['image'])) {
        $imgPath = __DIR__ . '/../' . $story['image'];
        if (is_file($imgPath)) { @unlink($imgPath); }
    }
    
    // Delete story
    $stmt = $pdo->prepare('DELETE FROM success_stories WHERE id = ?');
    $stmt->execute([$storyId]);
    
    ActivityLogger::logDelete('Success Story', 'Alumni deleted success story', [
        'story_id' => $storyId,
        'user_id' => (int)$user['id']
    ]);
    
    echo json_encode(['success' => true, 'message' => 'Story deleted successfully.']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to delete story.']);
}
?>


