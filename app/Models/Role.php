<?php

declare(strict_types=1);

namespace App\Models;

use Core\Model;
use PDOException;

class Role extends Model
{
    public function all(): array
    {
        return $this->paginate(1, 1000000)['data'];
    }

    public function paginate(int $page = 1, int $perPage = 5): array
    {
        $page = max(1, $page);
        $allowed = [5, 10, 20, 50, 100];
        $perPage = in_array($perPage, $allowed, true) ? $perPage : 5;
        $offset = ($page - 1) * $perPage;

        $countStmt = $this->db->query("SELECT COUNT(*) AS total FROM roles");
        $total = (int) ($countStmt->fetch()['total'] ?? 0);

        $stmt = $this->db->prepare("
            SELECT 
                r.*,
                COUNT(DISTINCT ur.user_id) AS total_users,
                COUNT(DISTINCT rp.permission_id) AS total_permissions
            FROM roles r
            LEFT JOIN user_roles ur ON ur.role_id = r.id
            LEFT JOIN role_permissions rp ON rp.role_id = r.id
            GROUP BY r.id, r.name, r.code, r.description, r.is_active, r.created_at, r.updated_at
            ORDER BY r.id ASC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':limit', $perPage, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();

        return [
            'data' => $stmt->fetchAll(),
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => max(1, (int) ceil($total / $perPage)),
        ];
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

    public function findById(int $id): array|false
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM roles
            WHERE id = :id
            LIMIT 1
        ");
        $stmt->execute(['id' => $id]);

        return $stmt->fetch();
    }

    public function codeExists(string $code, ?int $excludeId = null): bool
    {
        $sql = "SELECT id FROM roles WHERE code = :code";
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

    public function getPermissionIds(int $roleId): array
    {
        $stmt = $this->db->prepare("
            SELECT permission_id
            FROM role_permissions
            WHERE role_id = :role_id
        ");
        $stmt->execute(['role_id' => $roleId]);

        return array_map('intval', array_column($stmt->fetchAll(), 'permission_id'));
    }

    public function create(array $data): int|false
    {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("
                INSERT INTO roles (name, code, description, is_active)
                VALUES (:name, :code, :description, :is_active)
            ");
            $stmt->execute([
                'name' => $data['name'],
                'code' => $data['code'],
                'description' => $data['description'] ?: null,
                'is_active' => $data['is_active'],
            ]);

            $roleId = (int) $this->db->lastInsertId();

            $this->syncPermissions($roleId, $data['permission_ids'] ?? []);

            $this->db->commit();
            return $roleId;
        } catch (PDOException $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function update(int $id, array $data): bool
    {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("
                UPDATE roles
                SET
                    name = :name,
                    code = :code,
                    description = :description,
                    is_active = :is_active
                WHERE id = :id
            ");
            $stmt->execute([
                'id' => $id,
                'name' => $data['name'],
                'code' => $data['code'],
                'description' => $data['description'] ?: null,
                'is_active' => $data['is_active'],
            ]);

            $this->syncPermissions($id, $data['permission_ids'] ?? []);

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            return false;
        }
    }

    private function syncPermissions(int $roleId, array $permissionIds): void
    {
        $deleteStmt = $this->db->prepare("DELETE FROM role_permissions WHERE role_id = :role_id");
        $deleteStmt->execute(['role_id' => $roleId]);

        if (empty($permissionIds)) {
            return;
        }

        $insertStmt = $this->db->prepare("
            INSERT INTO role_permissions (role_id, permission_id)
            VALUES (:role_id, :permission_id)
        ");

        foreach ($permissionIds as $permissionId) {
            $insertStmt->execute([
                'role_id' => $roleId,
                'permission_id' => (int) $permissionId,
            ]);
        }
    }
}