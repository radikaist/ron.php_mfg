<?php

declare(strict_types=1);

use App\Models\Menu;
use Throwable;

require_once APP_PATH . '/Models/Menu.php';

function sidebar_menu(): array
{
    try {
        $menuModel = new Menu();
        $tree = $menuModel->sidebarTree();

        $result = [];

        foreach ($tree as $item) {
            $permission = $item['permission_code'];

            $children = [];
            foreach ($item['children'] as $child) {
                $childPermission = $child['permission_code'];

                if ($childPermission === null || $childPermission === '' || can($childPermission)) {
                    $children[] = [
                        'title' => $child['title'],
                        'url' => base_url($child['url']),
                        'permission' => $childPermission,
                        'icon' => $child['icon'],
                        'description' => '',
                    ];
                }
            }

            $canShowParent = $permission === null || $permission === '' || can($permission);

            if (!$canShowParent && empty($children)) {
                continue;
            }

            $result[] = [
                'title' => $item['title'],
                'url' => base_url($item['url']),
                'permission' => $permission,
                'icon' => $item['icon'],
                'description' => '',
                'children' => $children,
            ];
        }

        if (!empty($result)) {
            return $result;
        }
    } catch (Throwable $e) {
        // fallback ke menu default jika tabel menus belum ada
    }

    return sidebar_menu_fallback();
}

function sidebar_menu_fallback(): array
{
    $items = [
        [
            'title' => 'Dashboard',
            'url' => base_url('dashboard'),
            'permission' => 'dashboard.view',
            'icon' => '🏠',
            'description' => 'Ringkasan sistem',
            'children' => [],
        ],
        [
            'title' => 'Users',
            'url' => base_url('users'),
            'permission' => 'users.view',
            'icon' => '👤',
            'description' => 'Manajemen pengguna',
            'children' => [],
        ],
        [
            'title' => 'Roles',
            'url' => base_url('roles'),
            'permission' => 'roles.view',
            'icon' => '🛡️',
            'description' => 'Hak akses per role',
            'children' => [],
        ],
        [
            'title' => 'Permissions',
            'url' => base_url('permissions'),
            'permission' => 'permissions.view',
            'icon' => '🔑',
            'description' => 'Daftar izin akses',
            'children' => [],
        ],
        [
            'title' => 'RBAC Health',
            'url' => base_url('rbac/health'),
            'permission' => 'rbac.health.view',
            'icon' => '🩺',
            'description' => 'Audit kesehatan RBAC',
            'children' => [],
        ],
        [
            'title' => 'Menu Management',
            'url' => base_url('menus'),
            'permission' => 'menus.view',
            'icon' => '📚',
            'description' => 'Pengaturan menu sidebar',
            'children' => [],
        ],
        [
            'title' => 'Inventory',
            'url' => base_url('inventory'),
            'permission' => 'inventory.view',
            'icon' => '📦',
            'description' => 'Stok dan mutasi barang',
            'children' => [],
        ],
        [
            'title' => 'Production Orders',
            'url' => base_url('production-orders'),
            'permission' => 'production_orders.view',
            'icon' => '🏭',
            'description' => 'SPK dan proses produksi',
            'children' => [],
        ],
        [
            'title' => 'Quality Control',
            'url' => base_url('qc'),
            'permission' => 'qc.view',
            'icon' => '✅',
            'description' => 'Pemeriksaan kualitas',
            'children' => [],
        ],
    ];

    return array_values(array_filter($items, function (array $item): bool {
        $permission = $item['permission'] ?? '';
        return $permission === '' || can($permission);
    }));
}