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
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['comment_id']) || !isset($_POST['comment'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$commentId = (int)$_POST['comment_id'];
$newComment = trim($_POST['comment']);

if (empty($newComment)) {
    echo json_encode(['success' => false, 'message' => 'Comment cannot be empty']);
    exit;
}

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
        echo json_encode(['success' => false, 'message' => 'You can only edit your own comments']);
        exit;
    }
    
    // Update the comment
    $stmt = $pdo->prepare("UPDATE forum_comments SET comment = ? WHERE id = ?");
    $stmt->execute([$newComment, $commentId]);
    
    echo json_encode(['success' => true, 'message' => 'Comment updated successfully']);
    
} catch (Exception $e) {
    error_log("Error updating comment: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Failed to update comment: ' . $e->getMessage()]);
}
?>
