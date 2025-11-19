<?php
declare(strict_types=1);
require_once __DIR__ . '/inc/auth.php';
require_once __DIR__ . '/inc/logger.php';

// Log logout before destroying session
$username = $_SESSION['username'] ?? 'Unknown';
ActivityLogger::logLogout($username);

logout();
header('Location: /scratch/');
exit;


