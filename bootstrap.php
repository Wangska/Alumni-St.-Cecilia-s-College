<?php
declare(strict_types=1);

// Start session with secure settings
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

// Load autoloader
require __DIR__ . '/autoload.php';

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
}

// Helper functions
function csrf_token(): string
{
    return $_SESSION['csrf_token'] ?? '';
}

function e(string $s): string
{
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function is_logged_in(): bool
{
    return isset($_SESSION['user']);
}

function is_admin(): bool
{
    $user = current_user();
    return $user && (int)($user['type'] ?? 3) === 1;
}

