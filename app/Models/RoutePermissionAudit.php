<?php

declare(strict_types=1);

namespace App\Models;

use Core\Database;
use PDO;
use Throwable;

class RoutePermissionAudit
{
    public function getUndocumentedRoutes(): array
    {
        $routes = require ROUTES_PATH . '/web.php';

        $permissionModel = new Permission();
        $permissions = $permissionModel->all();
        $permissionCodes = array_map(static fn(array $item): string => (string) $item['code'], $permissions);

        $ignoredRoutes = [
            'GET /',
            'GET /login',
            'POST /login',
            'POST /logout',
        ];

        $results = [];

        foreach ($routes as $route) {
            [$method, $uri] = $route;

            $routeKey = strtoupper($method) . ' ' . $uri;

            if (in_array($routeKey, $ignoredRoutes, true)) {
                continue;
            }

            $normalized = trim($uri, '/');

            if ($normalized === '') {
                continue;
            }

            $segments = explode('/', $normalized);
            $module = str_replace('-', '_', $segments[0] ?? 'general');

            $suggestedPermission = $this->guessPermissionCode($method, $uri, $module);

            $exists = in_array($suggestedPermission, $permissionCodes, true);

            if (!$exists) {
                $results[] = [
                    'method' => strtoupper($method),
                    'uri' => $uri,
                    'module' => $module,
                    'suggested_code' => $suggestedPermission,
                    'suggested_name' => $this->guessPermissionName($method, $uri, $module),
                    'description' => 'Auto-generated from route audit.',
                ];
            }
        }

        return $results;
    }

    public function generatePermission(string $name, string $code, string $module, string $description = ''): bool
    {
        $db = Database::connect();

        $stmt = $db->prepare("
            SELECT id
            FROM permissions
            WHERE code = :code
            LIMIT 1
        ");
        $stmt->execute(['code' => $code]);

        if ($stmt->fetch()) {
            return false;
        }

        $insert = $db->prepare("
            INSERT INTO permissions (name, code, module, description, is_active)
            VALUES (:name, :code, :module, :description, 1)
        ");

        return $insert->execute([
            'name' => $name,
            'code' => $code,
            'module' => $module,
            'description' => $description ?: 'Generated automatically from route audit.',
        ]);
    }

    public function generateAllMissingPermissions(): array
    {
        $db = Database::connect();
        $routes = $this->getUndocumentedRoutes();

        $created = 0;
        $skipped = 0;

        try {
            $db->beginTransaction();

            foreach ($routes as $route) {
                $ok = $this->generatePermission(
                    $route['suggested_name'],
                    $route['suggested_code'],
                    $route['module'],
                    $route['description']
                );

                if ($ok) {
                    $created++;
                } else {
                    $skipped++;
                }
            }

            $db->commit();

            return [
                'created' => $created,
                'skipped' => $skipped,
            ];
        } catch (Throwable $e) {
            if ($db instanceof PDO && $db->inTransaction()) {
                $db->rollBack();
            }

            return [
                'created' => 0,
                'skipped' => count($routes),
            ];
        }
    }

    private function guessPermissionCode(string $method, string $uri, string $module): string
    {
        $method = strtoupper($method);
        $normalized = trim($uri, '/');

        if ($normalized === 'dashboard') {
            return 'dashboard.view';
        }

        if (str_ends_with($normalized, '/create')) {
            return $module . '.create';
        }

        if (str_ends_with($normalized, '/store')) {
            return $module . '.create';
        }

        if (str_ends_with($normalized, '/edit')) {
            return $module . '.edit';
        }

        if (str_ends_with($normalized, '/update')) {
            return $module . '.edit';
        }

        if (str_ends_with($normalized, '/delete')) {
            return $module . '.delete';
        }

        if ($method === 'GET') {
            return $module . '.view';
        }

        if ($method === 'POST') {
            return $module . '.create';
        }

        return $module . '.view';
    }

    private function guessPermissionName(string $method, string $uri, string $module): string
    {
        $code = $this->guessPermissionCode($method, $uri, $module);

        if (str_ends_with($code, '.view')) {
            return 'View ' . ucwords(str_replace('_', ' ', $module));
        }

        if (str_ends_with($code, '.create')) {
            return 'Create ' . ucwords(str_replace('_', ' ', $module));
        }

        if (str_ends_with($code, '.edit')) {
            return 'Edit ' . ucwords(str_replace('_', ' ', $module));
        }

        if (str_ends_with($code, '.delete')) {
            return 'Delete ' . ucwords(str_replace('_', ' ', $module));
        }

        return ucwords(str_replace('_', ' ', $module));
    }
}