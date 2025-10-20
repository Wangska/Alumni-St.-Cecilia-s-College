<?php
declare(strict_types=1);

// Database configuration (reads from env via config/database.php for deploys like Coolify)
$dbConfig = require __DIR__ . '/../config/database.php';

// Start session early for auth and CSRF with secure settings
if (session_status() === PHP_SESSION_NONE) {
    // Secure session configuration
    ini_set('session.cookie_httponly', '1');
    ini_set('session.use_only_cookies', '1');
    ini_set('session.cookie_secure', '0'); // Set to 1 if using HTTPS
    ini_set('session.cookie_samesite', 'Lax');
    
    // Set session lifetime to 30 days (in seconds)
    ini_set('session.gc_maxlifetime', '2592000'); // 30 days
    ini_set('session.cookie_lifetime', '2592000'); // 30 days
    
    session_start();
    
    // Regenerate session ID periodically for security
    if (!isset($_SESSION['last_regeneration'])) {
        $_SESSION['last_regeneration'] = time();
    } elseif (time() - $_SESSION['last_regeneration'] > 3600) { // Every hour
        session_regenerate_id(true);
        $_SESSION['last_regeneration'] = time();
    }
}

function get_pdo(): PDO {
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }
    $driver = $GLOBALS['dbConfig']['driver'] ?? 'mysql';
    $host = $GLOBALS['dbConfig']['host'] ?? '127.0.0.1';
    $port = (int)($GLOBALS['dbConfig']['port'] ?? 3306);
    $name = $GLOBALS['dbConfig']['database'] ?? '';
    $charset = $GLOBALS['dbConfig']['charset'] ?? 'utf8mb4';
    $user = $GLOBALS['dbConfig']['username'] ?? '';
    $pass = $GLOBALS['dbConfig']['password'] ?? '';

    $dsn = sprintf('%s:host=%s;port=%d;dbname=%s;charset=%s', $driver, $host, $port, $name, $charset);
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    $pdo = new PDO($dsn, $user, $pass, $options);
    return $pdo;
}

function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf_token'];
}

function require_csrf(): void {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $sent = $_POST['csrf_token'] ?? '';
        if (!$sent || !hash_equals($_SESSION['csrf_token'] ?? '', $sent)) {
            http_response_code(400);
            exit('Invalid CSRF token');
        }
    }
}

function e(string $s): string { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }


