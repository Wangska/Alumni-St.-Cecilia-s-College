<?php
declare(strict_types=1);

use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;

$router = new Router();

// Public routes
$router->get('/', HomeController::class, 'index');
$router->post('/auth/login', AuthController::class, 'login');
$router->post('/auth/register', AuthController::class, 'register');
$router->get('/auth/logout', AuthController::class, 'logout');

// Protected routes
$router->get('/dashboard', DashboardController::class, 'index');

return $router;

