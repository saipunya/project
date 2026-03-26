<?php

declare(strict_types=1);

return [
    'host' => getenv('DB_HOST') ?: '103.30.127.74',
    'port' => (int) (getenv('DB_PORT') ?: 3306),
    'database' => getenv('DB_NAME') ?: 'naimet_db',
    'username' => getenv('DB_USER') ?: 'naimet_user',
    'password' => getenv('DB_PASS') ?: 'sumet4631022',
    'charset' => 'utf8mb4',
];
