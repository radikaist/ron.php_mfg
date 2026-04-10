<?php

declare(strict_types=1);

function sidebar_menu(): array
{
    $items = [
        [
            'title' => 'Dashboard',
            'url' => base_url('dashboard'),
            'permission' => 'dashboard.view',
        ],
        [
            'title' => 'Users',
            'url' => base_url('users'),
            'permission' => 'users.view',
        ],
        [
            'title' => 'Roles',
            'url' => base_url('roles'),
            'permission' => 'roles.view',
        ],
        [
            'title' => 'Permissions',
            'url' => base_url('permissions'),
            'permission' => 'permissions.view',
        ],
        [
            'title' => 'Inventory',
            'url' => base_url('inventory'),
            'permission' => 'inventory.view',
        ],
        [
            'title' => 'Production Orders',
            'url' => base_url('production-orders'),
            'permission' => 'production_orders.view',
        ],
        [
            'title' => 'Quality Control',
            'url' => base_url('qc'),
            'permission' => 'qc.view',
        ],
    ];

    return array_values(array_filter($items, function (array $item): bool {
        return can($item['permission']);
    }));
}