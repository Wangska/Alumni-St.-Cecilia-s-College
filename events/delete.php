<?php
declare(strict_types=1);
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';
require_once __DIR__ . '/../inc/logger.php';
require_admin();
$pdo = get_pdo();
// Support both GET and POST methods
$id = (int)($_POST['id'] ?? $_GET['id'] ?? 0);
if ($id > 0) {
    // Verify CSRF token for POST requests
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
            die('Invalid CSRF token');
        }
    }
    // Get event name before deleting
    $stmt = $pdo->prepare('SELECT title FROM events WHERE id=?');
    $stmt->execute([$id]);
    $event = $stmt->fetch(PDO::FETCH_ASSOC);
    $eventName = $event['title'] ?? 'Unknown';
    
    // cascade deletes handled by FK for event_commits
    $stmt = $pdo->prepare('DELETE FROM events WHERE id=?');
    $stmt->execute([$id]);
    
    // Log the deletion
    ActivityLogger::logDelete('Event', $eventName);
}
$_SESSION['deleted'] = 'Event deleted successfully!';
header('Location: /scratch/admin.php?page=events');
exit;


