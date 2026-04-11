<?php

declare(strict_types=1);

use App\Models\Menu;

require_once APP_PATH . '/Models/Menu.php';

function sidebar_menu(): array
{
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

    return $result;
}