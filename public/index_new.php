<?php
declare(strict_types=1);

// Load bootstrap
require __DIR__ . '/../bootstrap.php';

// Load routes
$router = require __DIR__ . '/../routes/web.php';

// Dispatch request
$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

$router->dispatch($requestUri, $requestMethod);

