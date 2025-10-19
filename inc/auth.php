<?php
declare(strict_types=1);
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/logger.php';

function current_user(): ?array {
    return $_SESSION['user'] ?? null;
}

function require_login(): void {
    if (!current_user()) {
        header('Location: /scratch/login.php');
        exit;
    }
}

function attempt_login(string $username, string $password): bool {
    // passwords in DB are md5 per provided dump. Support md5 and plaintext (in case of manual inserts),
    // and auto-seed an admin account if missing.
    $pdo = get_pdo();
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ? LIMIT 1');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if (!$user && $username === 'admin') {
        // Seed default admin if not present
        $seed = $pdo->prepare('INSERT INTO users (name, username, password, type, auto_generated_pass, alumnus_id) VALUES (?,?,?,?,?,?)');
        $seed->execute(['Admin', 'admin', md5('admin123'), 1, '', null]);
        $stmt->execute([$username]);
        $user = $stmt->fetch();
    }

    if (!$user) return false;

    $md5 = md5($password);
    $stored = (string)$user['password'];
    $matches = hash_equals($stored, $md5) || hash_equals($stored, $password);
    if ($matches) {
        $_SESSION['user'] = [
            'id' => (int)$user['id'],
            'name' => $user['name'],
            'username' => $user['username'],
            'type' => (int)$user['type'],
            'alumnus_id' => (int)($user['alumnus_id'] ?? 0),
        ];
        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['username'] = $user['username'];
        
        // Log the login activity
        ActivityLogger::logLogin($user['username']);
        
        return true;
    }
    return false;
}

function logout(): void {
    // Log the logout activity before destroying session
    if (isset($_SESSION['username'])) {
        ActivityLogger::logLogout($_SESSION['username']);
    }
    
    $_SESSION = [];
    session_destroy();
}

function require_admin(): void {
    require_login();
    $u = current_user();
    if (($u['type'] ?? 3) !== 1) {
        http_response_code(403);
        exit('Forbidden');
    }
}

function is_admin(): bool {
    $user = current_user();
    return $user && (int)($user['type'] ?? 3) === 1;
}


