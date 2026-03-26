<?php

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('BASE_PATH', dirname(__DIR__));

define('APP_PATH', BASE_PATH . '/app');

define('VIEW_PATH', BASE_PATH . '/views');

spl_autoload_register(static function (string $class): void {
    $prefix = 'App\\';
    $baseDir = APP_PATH . '/';

    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (is_file($file)) {
        require_once $file;
    }
});
