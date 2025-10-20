<?php
declare(strict_types=1);

return [
    'host' => getenv('DB_HOST') ?: '127.0.0.1',
    'database' => getenv('DB_DATABASE') ?: 'sccalumni_db',
    'username' => getenv('DB_USERNAME') ?: 'root',
    'password' => getenv('DB_PASSWORD') ?: '',
    'port' => (int)(getenv('DB_PORT') ?: 3306),
    'driver' => getenv('DB_CONNECTION') ?: 'mysql',
    'charset' => 'utf8mb4',
];

