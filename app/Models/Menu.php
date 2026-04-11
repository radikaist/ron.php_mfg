<?php

declare(strict_types=1);

namespace App\Models;

use Core\Model;

class Menu extends Model
{
    public function all(): array
    {
        $stmt = $this->db->query("
            SELECT 
                m.id,
                m.parent_id,
                p.title AS parent_title,
                m.title,
                m.url,
                m.icon,
                m.permission_code,
                m.sort_order,
                m.is_active
            FROM menus m
            LEFT JOIN menus p ON p.id = m.parent_id
            ORDER BY COALESCE(m.parent_id, m.id) ASC, m.parent_id ASC, m.sort_order ASC, m.id ASC
        ");

        return $stmt->fetchAll();
    }

    public function sidebarTree(): array
    {
        $stmt = $this->db->query("
            SELECT
                id,
                parent_id,
                title,
                url,
                icon,
                permission_code,
                sort_order
            FROM menus
            WHERE is_active = 1
            ORDER BY parent_id ASC, sort_order ASC, id ASC
        ");

        $rows = $stmt->fetchAll();

        $parents = [];
        $children = [];

        foreach ($rows as $row) {
            if ($row['parent_id'] === null) {
                $parents[(int) $row['id']] = [
                    'id' => (int) $row['id'],
                    'title' => $row['title'],
                    'url' => $row['url'],
                    'icon' => $row['icon'] ?: '•',
                    'permission_code' => $row['permission_code'],
                    'sort_order' => (int) $row['sort_order'],
                    'children' => [],
                ];
            } else {
                $children[] = [
                    'id' => (int) $row['id'],
                    'parent_id' => (int) $row['parent_id'],
                    'title' => $row['title'],
                    'url' => $row['url'],
                    'icon' => $row['icon'] ?: '•',
                    'permission_code' => $row['permission_code'],
                    'sort_order' => (int) $row['sort_order'],
                ];
            }
        }

        foreach ($children as $child) {
            $parentId = $child['parent_id'];

            if (isset($parents[$parentId])) {
                $parents[$parentId]['children'][] = $child;
            }
        }

        return array_values($parents);
    }
}