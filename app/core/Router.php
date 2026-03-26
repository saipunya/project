<?php

declare(strict_types=1);

namespace App\Core;

use App\Helpers\Auth;

final class Router
{
    private array $routes;

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';

        if ($path === '/index.php' || $path === '/public/index.php') {
            $path = '/';
        } elseif (str_starts_with($path, '/index.php/')) {
            $path = substr($path, strlen('/index.php')) ?: '/';
        } elseif (str_starts_with($path, '/public/index.php/')) {
            $path = substr($path, strlen('/public/index.php')) ?: '/';
        }

        if ($path !== '/' && str_ends_with($path, '/')) {
            $path = rtrim($path, '/');
        }

        foreach ($this->routes as $route) {
            if (strtoupper($route['method']) !== strtoupper($method)) {
                continue;
            }

            $pattern = preg_replace('#\{[a-zA-Z_]+\}#', '([\\w-]+)', $route['path']);
            $pattern = '#^' . $pattern . '$#';

            if (!preg_match($pattern, $path, $matches)) {
                continue;
            }

            array_shift($matches);

            if (!empty($route['auth']) && !Auth::check()) {
                header('Location: /login');
                exit;
            }

            if (!empty($route['roles']) && !Auth::hasRole($route['roles'])) {
                http_response_code(403);
                echo 'Forbidden';
                return;
            }

            [$controller, $action] = explode('@', $route['handler']);
            $class = 'App\\Controllers\\' . $controller;
            $controllerInstance = new $class();
            call_user_func_array([$controllerInstance, $action], $matches);
            return;
        }

        http_response_code(404);
        echo 'Route not found';
    }
}
