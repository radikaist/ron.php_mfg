<?php

declare(strict_types=1);

namespace App\Models;

use Core\Model;

class Role extends Model
{
    public function getUserRoles(int|string $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT r.*
            FROM roles r
            INNER JOIN user_roles ur ON ur.role_id = r.id
            WHERE ur.user_id = :user_id
              AND r.is_active = 1
            ORDER BY r.name ASC
        ");

        $stmt->execute([
            'user_id' => $userId,
        ]);

        return $stmt->fetchAll();
    }

    public function userHasRole(int|string $userId, string $roleCode): bool
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) AS total
            FROM roles r
            INNER JOIN user_roles ur ON ur.role_id = r.id
            WHERE ur.user_id = :user_id
              AND r.code = :role_code
              AND r.is_active = 1
        ");

        $stmt->execute([
            'user_id' => $userId,
            'role_code' => $roleCode,
        ]);

        $row = $stmt->fetch();

        return (int) ($row['total'] ?? 0) > 0;
    }
}