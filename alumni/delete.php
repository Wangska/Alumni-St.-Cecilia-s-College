<?php
declare(strict_types=1);
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';
require_once __DIR__ . '/../inc/logger.php';
require_admin();
$pdo = get_pdo();
// Support both GET (from alumni page) and POST (from users/reject modal)
$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);

if ($id > 0) {
    // Get alumni info before deleting
    $stmt = $pdo->prepare('SELECT firstname, lastname FROM alumnus_bio WHERE id=?');
    $stmt->execute([$id]);
    $alumni = $stmt->fetch(PDO::FETCH_ASSOC);
    $alumniName = 'Unknown';
    if ($alumni) {
        $alumniName = trim($alumni['firstname'] . ' ' . $alumni['lastname']);
    }
    
    // Delete alumni
    $stmt = $pdo->prepare('DELETE FROM alumnus_bio WHERE id=?');
    $stmt->execute([$id]);
    
    // Log the deletion
    ActivityLogger::logDelete('Alumni', $alumniName);
}

// Check if coming from users page (pending rejection)
$fromUsers = isset($_POST['id']);
$_SESSION['deleted'] = $fromUsers ? 'Alumni rejected successfully!' : 'Alumni deleted successfully!';
$redirectPage = $fromUsers ? 'users' : 'alumni';

header("Location: /scratch/admin.php?page={$redirectPage}");
exit;


