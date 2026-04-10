<?php

declare(strict_types=1);

namespace App\Models;

use Core\Model;

class User extends Model
{
    public function findByUsername(string $username): array|false
    {
        $sql = "
            SELECT 
                u.*
            FROM users u
            WHERE u.username = :username
              AND u.is_active = 1
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'username' => $username,
        ]);

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
        $stmt->execute([
            'id' => $userId,
        ]);

        $user = $stmt->fetch();

        if (!$user) {
            return false;
        }

        $user['roles'] = !empty($user['roles']) ? explode(',', $user['roles']) : [];
        $user['permissions'] = !empty($user['permissions']) ? explode(',', $user['permissions']) : [];

        return $user;
    }
}