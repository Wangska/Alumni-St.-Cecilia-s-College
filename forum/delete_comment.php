<?php
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';

// Check if user is logged in
if (!current_user()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

$user = $_SESSION['user'];

// Check if POST data is provided
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['comment_id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$commentId = (int)$_POST['comment_id'];

$pdo = get_pdo();

try {
    // First, check if the comment exists and belongs to the current user
    $stmt = $pdo->prepare("SELECT id, user_id FROM forum_comments WHERE id = ?");
    $stmt->execute([$commentId]);
    $comment = $stmt->fetch();
    
    if (!$comment) {
        echo json_encode(['success' => false, 'message' => 'Comment not found']);
        exit;
    }
    
    if ($comment['user_id'] != $user['id']) {
        echo json_encode(['success' => false, 'message' => 'You can only delete your own comments']);
        exit;
    }
    
    // Delete the comment
    $stmt = $pdo->prepare("DELETE FROM forum_comments WHERE id = ?");
    $stmt->execute([$commentId]);
    
    echo json_encode(['success' => true, 'message' => 'Comment deleted successfully']);
    
} catch (Exception $e) {
    error_log("Error deleting comment: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Failed to delete comment. Please try again.']);
}
?>
