<?php

declare(strict_types=1);

namespace App\Models;

final class User extends BaseModel
{
    public function roleIdByName(string $roleName): ?int
    {
        $stmt = $this->db->prepare('SELECT id FROM roles WHERE name = :name LIMIT 1');
        $stmt->execute(['name' => $roleName]);
        $row = $stmt->fetch();

        return $row ? (int) $row['id'] : null;
    }

    public function findByUsername(string $username): ?array
    {
        $sql = 'SELECT u.*, r.name AS role_name
                FROM users u
                INNER JOIN roles r ON r.id = u.role_id
                WHERE u.username = :username
                LIMIT 1';

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['username' => $username]);
        $row = $stmt->fetch();

        return $row ?: null;
    }

    public function touchLastLogin(int $userId): void
    {
        $stmt = $this->db->prepare('UPDATE users SET last_login_at = NOW() WHERE id = :id');
        $stmt->execute(['id' => $userId]);
    }

    public function create(array $payload): int
    {
        $sql = 'INSERT INTO users (role_id, username, password_hash, full_name, is_active)
                VALUES (:role_id, :username, :password_hash, :full_name, :is_active)';

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'role_id' => $payload['role_id'],
            'username' => $payload['username'],
            'password_hash' => $payload['password_hash'],
            'full_name' => $payload['full_name'],
            'is_active' => $payload['is_active'] ?? 1,
        ]);

        return (int) $this->db->lastInsertId();
    }
}
