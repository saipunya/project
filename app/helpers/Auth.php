<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Models\User;

final class Auth
{
    public static function attempt(string $username, string $password): bool
    {
        $userModel = new User();
        $user = $userModel->findByUsername($username);

        if (!$user || !$user['is_active']) {
            return false;
        }

        if (!password_verify($password, $user['password_hash'])) {
            return false;
        }

        $_SESSION['user'] = [
            'id' => (int) $user['id'],
            'username' => $user['username'],
            'full_name' => $user['full_name'],
            'role' => $user['role_name'],
        ];

        $userModel->touchLastLogin((int) $user['id']);

        return true;
    }

    public static function check(): bool
    {
        return isset($_SESSION['user']);
    }

    public static function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    public static function hasRole(array $allowedRoles): bool
    {
        if (!self::check()) {
            return false;
        }

        return in_array($_SESSION['user']['role'], $allowedRoles, true);
    }

    public static function logout(): void
    {
        unset($_SESSION['user']);
        session_regenerate_id(true);
    }
}
