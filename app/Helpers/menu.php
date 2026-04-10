<?php

declare(strict_types=1);

function sidebar_menu(): array
{
    $items = [
        [
            'title' => 'Dashboard',
            'url' => base_url('dashboard'),
            'permission' => 'dashboard.view',
            'icon' => '🏠',
            'description' => 'Ringkasan sistem',
        ],
        [
            'title' => 'Users',
            'url' => base_url('users'),
            'permission' => 'users.view',
            'icon' => '👤',
            'description' => 'Manajemen pengguna',
        ],
        [
            'title' => 'Roles',
            'url' => base_url('roles'),
            'permission' => 'roles.view',
            'icon' => '🛡️',
            'description' => 'Hak akses per role',
        ],
        [
            'title' => 'Permissions',
            'url' => base_url('permissions'),
            'permission' => 'permissions.view',
            'icon' => '🔑',
            'description' => 'Daftar izin akses',
        ],
        [
            'title' => 'RBAC Health',
            'url' => base_url('rbac/health'),
            'permission' => 'permissions.view',
            'icon' => '🩺',
            'description' => 'Audit kesehatan RBAC',
        ],
        [
            'title' => 'Inventory',
            'url' => base_url('inventory'),
            'permission' => 'inventory.view',
            'icon' => '📦',
            'description' => 'Stok dan mutasi barang',
        ],
        [
            'title' => 'Production Orders',
            'url' => base_url('production-orders'),
            'permission' => 'production_orders.view',
            'icon' => '🏭',
            'description' => 'SPK dan proses produksi',
        ],
        [
            'title' => 'Quality Control',
            'url' => base_url('qc'),
            'permission' => 'qc.view',
            'icon' => '✅',
            'description' => 'Pemeriksaan kualitas',
        ],
    ];

    return array_values(array_filter($items, function (array $item): bool {
        return can($item['permission']);
    }));
}