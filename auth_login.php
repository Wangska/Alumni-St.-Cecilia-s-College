<?php
declare(strict_types=1);
require_once __DIR__ . '/inc/config.php';
require_once __DIR__ . '/inc/auth.php';
require_csrf();

$username = trim($_POST['username'] ?? '');
$password = (string)($_POST['password'] ?? '');

if ($username && $password) {
    // Check if user exists and get verification status
    $pdo = get_pdo();
    $stmt = $pdo->prepare('SELECT u.*, ab.status FROM users u LEFT JOIN alumnus_bio ab ON u.alumnus_id = ab.id WHERE u.username = ? LIMIT 1');
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if ($user) {
        // Check password
        $md5 = md5($password);
        $stored = (string)$user['password'];
        $passwordMatches = hash_equals($stored, $md5) || hash_equals($stored, $password);
        
        if ($passwordMatches) {
            // Check if account is verified (for alumni users)
            if ($user['type'] == 3 && $user['status'] != 1) {
                header('Location: /scratch/?login=unverified');
                exit;
            }
            
            // Login successful
            $_SESSION['user'] = [
                'id' => (int)$user['id'],
                'name' => $user['name'],
                'username' => $user['username'],
                'type' => (int)$user['type'],
            ];
            $_SESSION['user_id'] = (int)$user['id'];
            $_SESSION['username'] = $user['username'];
            
            // Log the login activity
            ActivityLogger::logLogin($user['username']);
            
            // Redirect based on user type
            if (is_admin()) {
                header('Location: /scratch/admin.php');
            } else {
                header('Location: /scratch/dashboard.php');
            }
            exit;
        } else {
            // Wrong password
            header('Location: /scratch/?login=incorrect');
            exit;
        }
    } else {
        // User not found
        header('Location: /scratch/?login=incorrect');
        exit;
    }
}

// Missing credentials
header('Location: /scratch/?login=incorrect');
exit;


