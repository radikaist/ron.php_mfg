<?php

declare(strict_types=1);

namespace App\Models;

use Core\Model;

class Permission extends Model
{
    public function getUserPermissions(int|string $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT DISTINCT p.*
            FROM permissions p
            INNER JOIN role_permissions rp ON rp.permission_id = p.id
            INNER JOIN user_roles ur ON ur.role_id = rp.role_id
            INNER JOIN roles r ON r.id = ur.role_id
            WHERE ur.user_id = :user_id
              AND r.is_active = 1
            ORDER BY p.module ASC, p.name ASC
        ");

        $stmt->execute([
            'user_id' => $userId,
        ]);

        return $stmt->fetchAll();
    }

    public function userCan(int|string $userId, string $permissionCode): bool
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(DISTINCT p.id) AS total
            FROM permissions p
            INNER JOIN role_permissions rp ON rp.permission_id = p.id
            INNER JOIN user_roles ur ON ur.role_id = rp.role_id
            INNER JOIN roles r ON r.id = ur.role_id
            WHERE ur.user_id = :user_id
              AND p.code = :permission_code
              AND r.is_active = 1
        ");

        $stmt->execute([
            'user_id' => $userId,
            'permission_code' => $permissionCode,
        ]);

        $row = $stmt->fetch();

        return (int) ($row['total'] ?? 0) > 0;
    }
}