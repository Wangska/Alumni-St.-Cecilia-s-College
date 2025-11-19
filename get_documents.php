<?php
// Dedicated endpoint for document fetching
// This file is simpler and doesn't require full admin.php authentication

// Start output buffering to prevent any HTML output
ob_start();

// Include config
require_once __DIR__ . '/inc/config.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in and is admin or alumni officer
$userType = $_SESSION['user']['type'] ?? 0;
if (!isset($_SESSION['user']) || ($userType != 1 && $userType != 2)) {
    ob_clean();
    header('Content-Type: application/json');
    echo json_encode([
        'error' => 'Unauthorized - Please log in as admin or alumni officer',
        'debug' => [
            'session_user' => isset($_SESSION['user']) ? $_SESSION['user'] : 'not set',
            'user_type' => $_SESSION['user']['type'] ?? 'not set'
        ]
    ]);
    exit;
}

// Get alumni ID
$alumnusId = (int)($_GET['alumni_id'] ?? 0);

if ($alumnusId <= 0) {
    ob_clean();
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Invalid alumni ID']);
    exit;
}

try {
    $pdo = get_pdo();
    $stmt = $pdo->prepare('SELECT * FROM alumni_documents WHERE alumnus_id = ? ORDER BY upload_date DESC');
    $stmt->execute([$alumnusId]);
    $documents = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    ob_clean();
    header('Content-Type: application/json');
    echo json_encode(['documents' => $documents]);
    exit;
    
} catch (Exception $e) {
    ob_clean();
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    exit;
}
?>
