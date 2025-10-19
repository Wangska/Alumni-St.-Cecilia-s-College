<?php
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';

// Set JSON header
header('Content-Type: application/json');

if (!current_user()) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

$pdo = get_pdo();

if (isset($_GET['topic_id']) && isset($_GET['offset'])) {
    $topicId = (int)$_GET['topic_id'];
    $offset = (int)$_GET['offset'];
    $limit = 4; // Load 4 more comments at a time
    
    try {
        // Get total count
        $countStmt = $pdo->prepare("SELECT COUNT(*) as total FROM forum_comments WHERE topic_id = ?");
        $countStmt->execute([$topicId]);
        $totalCount = $countStmt->fetch()['total'];
        
        // Get comments with offset
        $stmt = $pdo->prepare("SELECT fc.*, u.name as author_name FROM forum_comments fc LEFT JOIN users u ON fc.user_id = u.id WHERE fc.topic_id = ? ORDER BY fc.date_created ASC LIMIT ? OFFSET ?");
        $stmt->execute([$topicId, $limit, $offset]);
        $comments = $stmt->fetchAll();
        
        $hasMore = ($offset + $limit) < $totalCount;
        $remaining = $totalCount - ($offset + $limit);
        
        echo json_encode([
            'success' => true,
            'comments' => $comments,
            'hasMore' => $hasMore,
            'remaining' => $remaining,
            'total' => $totalCount
        ]);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Failed to fetch comments: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Missing parameters']);
}
?>
