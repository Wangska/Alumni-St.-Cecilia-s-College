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
    // Get announcement info before deleting
    $stmt = $pdo->prepare('SELECT * FROM announcements WHERE id=?');
    $stmt->execute([$id]);
    $announcement = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Determine announcement name
    $announcementName = 'Unknown';
    if ($announcement) {
        if (!empty($announcement['title'])) {
            $announcementName = $announcement['title'];
        } else {
            $announcementName = substr($announcement['content'] ?? '', 0, 50);
        }
    }
    
    // Delete announcement
    $stmt = $pdo->prepare('DELETE FROM announcements WHERE id=?');
    $stmt->execute([$id]);
    
    // Log the deletion
    ActivityLogger::logDelete('Announcement', $announcementName);
}
$_SESSION['deleted'] = 'Announcement deleted successfully!';
header('Location: /scratch/admin.php?page=announcements');
exit;


