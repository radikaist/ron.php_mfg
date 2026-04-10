<?php

declare(strict_types=1);

namespace App\Models;

use Core\Model;
use PDOException;

class User extends Model
{
    public function findByUsername(string $username): array|false
    {
        $sql = "
            SELECT u.*
            FROM users u
            WHERE u.username = :username
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['username' => $username]);

        return $stmt->fetch();
    }

    public function findWithRolesAndPermissionsById(int $userId): array|false
    {
        $sql = "
            SELECT 
                u.id,
                u.name,
                u.username,
                u.email,
                GROUP_CONCAT(DISTINCT r.code ORDER BY r.code SEPARATOR ',') AS roles,
                GROUP_CONCAT(DISTINCT p.code ORDER BY p.code SEPARATOR ',') AS permissions
            FROM users u
            LEFT JOIN user_roles ur ON ur.user_id = u.id
            LEFT JOIN roles r ON r.id = ur.role_id AND r.is_active = 1
            LEFT JOIN role_permissions rp ON rp.role_id = r.id
            LEFT JOIN permissions p ON p.id = rp.permission_id AND p.is_active = 1
            WHERE u.id = :id
              AND u.is_active = 1
            GROUP BY u.id, u.name, u.username, u.email
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $userId]);

        $user = $stmt->fetch();

        if (!$user) {
            return false;
        }

        $user['roles'] = !empty($user['roles']) ? explode(',', $user['roles']) : [];
        $user['permissions'] = !empty($user['permissions']) ? explode(',', $user['permissions']) : [];

        return $user;
    }

    public function all(): array
    {
        $sql = "
            SELECT 
                u.id,
                u.name,
                u.username,
                u.email,
                u.is_active,
                GROUP_CONCAT(DISTINCT r.name ORDER BY r.name SEPARATOR ', ') AS role_names
            FROM users u
            LEFT JOIN user_roles ur ON ur.user_id = u.id
            LEFT JOIN roles r ON r.id = ur.role_id
            GROUP BY u.id, u.name, u.username, u.email, u.is_active
            ORDER BY u.id DESC
        ";

        return $this->db->query($sql)->fetchAll();
    }

    public function findById(int $id): array|false
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM users
            WHERE id = :id
            LIMIT 1
        ");
        $stmt->execute(['id' => $id]);

        return $stmt->fetch();
    }

    public function getRoleIds(int $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT role_id
            FROM user_roles
            WHERE user_id = :user_id
        ");
        $stmt->execute(['user_id' => $userId]);

        return array_map('intval', array_column($stmt->fetchAll(), 'role_id'));
    }

    public function create(array $data): int|false
    {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("
                INSERT INTO users (name, username, email, password, is_active)
                VALUES (:name, :username, :email, :password, :is_active)
            ");

            $stmt->execute([
                'name' => $data['name'],
                'username' => $data['username'],
                'email' => $data['email'] ?: null,
                'password' => password_hash($data['password'], PASSWORD_DEFAULT),
                'is_active' => $data['is_active'],
            ]);

            $userId = (int) $this->db->lastInsertId();

            $this->syncRoles($userId, $data['role_ids'] ?? []);

            $this->db->commit();
            return $userId;
        } catch (PDOException $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function update(int $id, array $data): bool
    {
        try {
            $this->db->beginTransaction();

            $fields = "
                name = :name,
                username = :username,
                email = :email,
                is_active = :is_active
            ";

            $params = [
                'id' => $id,
                'name' => $data['name'],
                'username' => $data['username'],
                'email' => $data['email'] ?: null,
                'is_active' => $data['is_active'],
            ];

            if (!empty($data['password'])) {
                $fields .= ", password = :password";
                $params['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            }

            $stmt = $this->db->prepare("
                UPDATE users
                SET {$fields}
                WHERE id = :id
            ");

            $stmt->execute($params);

            $this->syncRoles($id, $data['role_ids'] ?? []);

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function usernameExists(string $username, ?int $excludeId = null): bool
    {
        $sql = "SELECT id FROM users WHERE username = :username";
        $params = ['username' => $username];

        if ($excludeId !== null) {
            $sql .= " AND id != :exclude_id";
            $params['exclude_id'] = $excludeId;
        }

        $sql .= " LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return (bool) $stmt->fetch();
    }

    private function syncRoles(int $userId, array $roleIds): void
    {
        $deleteStmt = $this->db->prepare("DELETE FROM user_roles WHERE user_id = :user_id");
        $deleteStmt->execute(['user_id' => $userId]);

        if (empty($roleIds)) {
            return;
        }

        $insertStmt = $this->db->prepare("
            INSERT INTO user_roles (user_id, role_id)
            VALUES (:user_id, :role_id)
        ");

        foreach ($roleIds as $roleId) {
            $insertStmt->execute([
                'user_id' => $userId,
                'role_id' => (int) $roleId,
            ]);
        }
    }
}