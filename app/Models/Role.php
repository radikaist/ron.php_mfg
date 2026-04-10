<?php

declare(strict_types=1);

namespace App\Models;

use Core\Model;

class Role extends Model
{
    public function all(): array
    {
        $sql = "
            SELECT 
                r.*,
                COUNT(DISTINCT ur.user_id) AS total_users,
                COUNT(DISTINCT rp.permission_id) AS total_permissions
            FROM roles r
            LEFT JOIN user_roles ur ON ur.role_id = r.id
            LEFT JOIN role_permissions rp ON rp.role_id = r.id
            GROUP BY r.id, r.name, r.code, r.description, r.is_active, r.created_at, r.updated_at
            ORDER BY r.id ASC
        ";

        return $this->db->query($sql)->fetchAll();
    }

    public function activeOptions(): array
    {
        $stmt = $this->db->query("
            SELECT id, name, code
            FROM roles
            WHERE is_active = 1
            ORDER BY name ASC
        ");

        return $stmt->fetchAll();
    }
}