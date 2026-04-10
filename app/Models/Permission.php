<?php

declare(strict_types=1);

namespace App\Models;

use Core\Model;
use PDOException;

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

    public function activeOptions(): array
    {
        $stmt = $this->db->query("
            SELECT id, name, code, module
            FROM permissions
            WHERE is_active = 1
            ORDER BY module ASC, name ASC
        ");

        return $stmt->fetchAll();
    }

    public function findById(int $id): array|false
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM permissions
            WHERE id = :id
            LIMIT 1
        ");
        $stmt->execute(['id' => $id]);

        return $stmt->fetch();
    }

    public function codeExists(string $code, ?int $excludeId = null): bool
    {
        $sql = "SELECT id FROM permissions WHERE code = :code";
        $params = ['code' => $code];

        if ($excludeId !== null) {
            $sql .= " AND id != :exclude_id";
            $params['exclude_id'] = $excludeId;
        }

        $sql .= " LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return (bool) $stmt->fetch();
    }

    public function create(array $data): int|false
    {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO permissions (name, code, module, description, is_active)
                VALUES (:name, :code, :module, :description, :is_active)
            ");
            $stmt->execute([
                'name' => $data['name'],
                'code' => $data['code'],
                'module' => $data['module'],
                'description' => $data['description'] ?: null,
                'is_active' => $data['is_active'],
            ]);

            return (int) $this->db->lastInsertId();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function update(int $id, array $data): bool
    {
        try {
            $stmt = $this->db->prepare("
                UPDATE permissions
                SET
                    name = :name,
                    code = :code,
                    module = :module,
                    description = :description,
                    is_active = :is_active
                WHERE id = :id
            ");
            $stmt->execute([
                'id' => $id,
                'name' => $data['name'],
                'code' => $data['code'],
                'module' => $data['module'],
                'description' => $data['description'] ?: null,
                'is_active' => $data['is_active'],
            ]);

            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}