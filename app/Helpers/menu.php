<?php

declare(strict_types=1);

use App\Models\Menu;
use Throwable;

if (defined('APP_PATH')) {
    $menuModelPath = APP_PATH . '/Models/Menu.php';
    if (file_exists($menuModelPath)) {
        require_once $menuModelPath;
    }
}

function sidebar_menu(): array
{
    try {
        if (!function_exists('auth')) {
            return [];
        }

        $currentUser = auth();

        if (empty($currentUser)) {
            return [];
        }

        if (!class_exists(Menu::class)) {
            return sidebar_menu_fallback();
        }

        $menuModel = new Menu();
        $tree = $menuModel->sidebarTree();

        $result = [];

        foreach ($tree as $item) {
            $permission = $item['permission_code'] ?? null;

            $children = [];
            foreach (($item['children'] ?? []) as $child) {
                $childPermission = $child['permission_code'] ?? null;

                if (menu_item_can_be_shown($childPermission)) {
                    $children[] = [
                        'title' => $child['title'] ?? '',
                        'url' => base_url($child['url'] ?? '#'),
                        'permission' => $childPermission,
                        'icon' => $child['icon'] ?? '•',
                        'description' => '',
                    ];
                }
            }

            if (!menu_item_can_be_shown($permission) && empty($children)) {
                continue;
            }

            $result[] = [
                'title' => $item['title'] ?? '',
                'url' => base_url($item['url'] ?? '#'),
                'permission' => $permission,
                'icon' => $item['icon'] ?? '•',
                'description' => '',
                'children' => $children,
            ];
        }

        if (!empty($result)) {
            return $result;
        }
    } catch (Throwable $e) {
        return sidebar_menu_fallback();
    }

    return sidebar_menu_fallback();
}

function menu_item_can_be_shown(?string $permissionCode): bool
{
    try {
        if ($permissionCode === null || $permissionCode === '') {
            return true;
        }

        if (!function_exists('can')) {
            return false;
        }

        return can($permissionCode);
    } catch (Throwable $e) {
        return false;
    }
}

function sidebar_menu_fallback(): array
{
    try {
        if (!function_exists('auth')) {
            return [];
        }

        $currentUser = auth();

        if (empty($currentUser)) {
            return [];
        }

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
            return menu_item_can_be_shown($item['permission'] ?? null);
        }));
    } catch (Throwable $e) {
        return [];
    }
}