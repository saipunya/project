<?php

declare(strict_types=1);

namespace App\Helpers;

final class Response
{
    public static function redirect(string $path): void
    {
        header('Location: ' . $path);
        exit;
    }

    public static function view(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        require VIEW_PATH . '/layouts/header.php';
        require VIEW_PATH . '/' . $view . '.php';
        require VIEW_PATH . '/layouts/footer.php';
    }

    public static function json(array $payload, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
