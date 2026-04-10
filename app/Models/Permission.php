<?php

declare(strict_types=1);

namespace App\Models;

use Core\Model;
use PDOException;

class Permission extends Model
{
    public function all(): array
    {
        return $this->paginate(1, 1000000)['data'];
    }

    public function paginate(int $page = 1, int $perPage = 5, string $search = ''): array
    {
        $page = max(1, $page);
        $allowed = [5, 10, 20, 50, 100];
        $perPage = in_array($perPage, $allowed, true) ? $perPage : 5;
        $offset = ($page - 1) * $perPage;
        $search = trim($search);

        $whereSql = '';
        $params = [];

        if ($search !== '') {
            $whereSql = "
                WHERE (
                    p.name LIKE :search
                    OR p.code LIKE :search
                    OR p.module LIKE :search
                    OR p.description LIKE :search
                )
            ";
            $params['search'] = '%' . $search . '%';
        }

        $countStmt = $this->db->prepare("
            SELECT COUNT(*) AS total
            FROM permissions p
            {$whereSql}
        ");
        $countStmt->execute($params);
        $total = (int) ($countStmt->fetch()['total'] ?? 0);

        $stmt = $this->db->prepare("
            SELECT 
                p.*,
                COUNT(DISTINCT rp.role_id) AS total_roles
            FROM permissions p
            LEFT JOIN role_permissions rp ON rp.permission_id = p.id
            {$whereSql}
            GROUP BY p.id, p.name, p.code, p.module, p.description, p.is_active, p.created_at, p.updated_at
            ORDER BY p.module ASC, p.name ASC
            LIMIT :limit OFFSET :offset
        ");

        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value, \PDO::PARAM_STR);
        }

        $stmt->bindValue(':limit', $perPage, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();

        return [
            'data' => $stmt->fetchAll(),
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'search' => $search,
            'total_pages' => max(1, (int) ceil($total / $perPage)),
        ];
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