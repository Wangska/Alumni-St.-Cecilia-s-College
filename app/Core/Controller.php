<?php
declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    protected function view(string $view, array $data = []): void
    {
        // Convert dot notation to path (e.g., 'admin.dashboard' => 'admin/dashboard')
        $viewPath = __DIR__ . '/../../views/' . str_replace('.', '/', $view) . '.php';
        
        if (!file_exists($viewPath)) {
            throw new \Exception("View not found: {$view} (path: {$viewPath})");
        }
        
        // Extract data to make it available in the view
        extract($data);
        
        require $viewPath;
    }

    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }

    protected function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function input(string $key, $default = null)
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    protected function validateCsrf(): bool
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? '';
            $sessionToken = $_SESSION['csrf_token'] ?? '';
            return hash_equals($sessionToken, $token);
        }
        return true;
    }

    protected function requireCsrf(): void
    {
        if (!$this->validateCsrf()) {
            http_response_code(403);
            die('Invalid CSRF token');
        }
    }
}

