<?php

declare(strict_types=1);

namespace App\Models;

use Core\Model;

class Dashboard extends Model
{
    public function counts(): array
    {
        return [
            'users' => $this->countTable('users'),
            'active_users' => $this->countWhere('users', 'is_active = 1'),
            'roles' => $this->countTable('roles'),
            'permissions' => $this->countTable('permissions'),
            'active_roles' => $this->countWhere('roles', 'is_active = 1'),
            'active_permissions' => $this->countWhere('permissions', 'is_active = 1'),
        ];
    }

    private function countTable(string $table): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) AS total FROM {$table}");
        $row = $stmt->fetch();

        return (int) ($row['total'] ?? 0);
    }

    private function countWhere(string $table, string $where): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) AS total FROM {$table} WHERE {$where}");
        $row = $stmt->fetch();

        return (int) ($row['total'] ?? 0);
    }
}