<?php
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';
require_once __DIR__ . '/../inc/logger.php';

// Set JSON header for AJAX responses
header('Content-Type: application/json');

if (!current_user()) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

$user = $_SESSION['user'];
$pdo = get_pdo();

if ($_POST && isset($_POST['comment']) && isset($_POST['topic_id'])) {
    $comment = trim($_POST['comment']);
    $topicId = (int)$_POST['topic_id'];
    
    if ($comment && $topicId > 0) {
        try {
            $stmt = $pdo->prepare("INSERT INTO forum_comments (topic_id, user_id, comment, date_created) VALUES (?, ?, ?, NOW())");
            $stmt->execute([$topicId, $user['id'], $comment]);
            
            // Get the comment ID
            $commentId = $pdo->lastInsertId();
            
            // Log the comment activity
            ActivityLogger::logCreate($user['username'], 'Forum Comment', 'Commented on topic ID: ' . $topicId);
            
            echo json_encode(['success' => true, 'message' => 'Comment posted successfully', 'comment_id' => $commentId]);
            exit;
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Failed to post comment: ' . $e->getMessage()]);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid comment data']);
        exit;
    }
}

// If no valid data
echo json_encode(['success' => false, 'message' => 'No comment data provided']);
exit;
?>
