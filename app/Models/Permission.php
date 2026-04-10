<?php

declare(strict_types=1);

namespace App\Models;

use Core\Model;

class Permission extends Model
{
    public function all(): array
    {
        $sql = "
            SELECT 
                p.*,
                COUNT(DISTINCT rp.role_id) AS total_roles
            FROM permissions p
            LEFT JOIN role_permissions rp ON rp.permission_id = p.id
            GROUP BY p.id, p.name, p.code, p.module, p.description, p.is_active, p.created_at, p.updated_at
            ORDER BY p.module ASC, p.name ASC
        ";

        return $this->db->query($sql)->fetchAll();
    }
}