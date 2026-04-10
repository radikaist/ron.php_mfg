<?php

declare(strict_types=1);

namespace App\Models;

use Core\Model;
use Throwable;

class RbacHealthCheck extends Model
{
    public function summary(): array
    {
        $routesWithoutPermission = count($this->routesWithoutPermission());
        $permissionsWithoutRoute = count($this->permissionsWithoutRoute());
        $rolesWithoutPermission = count($this->rolesWithoutPermission());
        $usersWithoutRole = count($this->usersWithoutRole());
        $inactiveRolesUsed = count($this->inactiveRolesStillAssigned());
        $inactivePermissionsUsed = count($this->inactivePermissionsStillAssigned());
        $controllerActionsWithoutPermissionCheck = count($this->controllerActionsWithoutPermissionCheck());
        $permissionMismatch = count($this->permissionMismatchInControllers());
        $controllerPermissionNotRegistered = count($this->controllerPermissionNotRegistered());
        $registeredButUnusedPermissions = count($this->registeredButUnusedPermissions());

        return [
            'routes_without_permission' => $routesWithoutPermission,
            'permissions_without_route' => $permissionsWithoutRoute,
            'roles_without_permission' => $rolesWithoutPermission,
            'users_without_role' => $usersWithoutRole,
            'inactive_roles_used' => $inactiveRolesUsed,
            'inactive_permissions_used' => $inactivePermissionsUsed,
            'controller_actions_without_permission_check' => $controllerActionsWithoutPermissionCheck,
            'permission_mismatch_in_controllers' => $permissionMismatch,
            'controller_permission_not_registered' => $controllerPermissionNotRegistered,
            'registered_but_unused_permissions' => $registeredButUnusedPermissions,
            'total_issues' => $routesWithoutPermission
                + $permissionsWithoutRoute
                + $rolesWithoutPermission
                + $usersWithoutRole
                + $inactiveRolesUsed
                + $inactivePermissionsUsed
                + $controllerActionsWithoutPermissionCheck
                + $permissionMismatch
                + $controllerPermissionNotRegistered
                + $registeredButUnusedPermissions,
        ];
    }

    public function paginateArray(array $items, int $page = 1, int $perPage = 5): array
    {
        $allowed = [5, 10, 20, 50, 100];
        $page = max(1, $page);
        $perPage = in_array($perPage, $allowed, true) ? $perPage : 5;

        $total = count($items);
        $offset = ($page - 1) * $perPage;
        $data = array_slice($items, $offset, $perPage);

        return [
            'data' => array_values($data),
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => max(1, (int) ceil($total / $perPage)),
        ];
    }

    public function filterArrayByKeyword(array $items, string $keyword): array
    {
        $keyword = trim(mb_strtolower($keyword));

        if ($keyword === '') {
            return $items;
        }

        return array_values(array_filter($items, function (array $item) use ($keyword): bool {
            foreach ($item as $value) {
                $text = mb_strtolower((string) $value);
                if (str_contains($text, $keyword)) {
                    return true;
                }
            }

            return false;
        }));
    }

    public function routesWithoutPermission(): array
    {
        $routes = require ROUTES_PATH . '/web.php';
        $permissionCodes = $this->registeredPermissionCodes();
        $ignoredRoutes = $this->ignoredRoutes();
        $results = [];

        foreach ($routes as $route) {
            if (!isset($route[0], $route[1])) {
                continue;
            }

            $method = (string) $route[0];
            $uri = (string) $route[1];

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
            $suggestedCode = $this->guessPermissionCode($method, $uri, $module);

            if (!in_array($suggestedCode, $permissionCodes, true)) {
                $results[] = [
                    'method' => strtoupper($method),
                    'uri' => $uri,
                    'module' => $module,
                    'suggested_code' => $suggestedCode,
                    'suggested_name' => $this->guessPermissionName($method, $uri, $module),
                    'description' => 'Generated from route audit.',
                ];
            }
        }

        return $results;
    }

    public function permissionsWithoutRoute(): array
    {
        $routes = require ROUTES_PATH . '/web.php';
        $routeCodes = [];

        foreach ($routes as $route) {
            if (!isset($route[0], $route[1])) {
                continue;
            }

            $method = (string) $route[0];
            $uri = (string) $route[1];

            $routeKey = strtoupper($method) . ' ' . $uri;
            if (in_array($routeKey, $this->ignoredRoutes(), true)) {
                continue;
            }

            $normalized = trim($uri, '/');
            if ($normalized === '') {
                continue;
            }

            $segments = explode('/', $normalized);
            $module = str_replace('-', '_', $segments[0] ?? 'general');
            $routeCodes[] = $this->guessPermissionCode($method, $uri, $module);
        }

        $routeCodes = array_unique($routeCodes);

        $stmt = $this->db->query("
            SELECT id, name, code, module, is_active
            FROM permissions
            ORDER BY module ASC, code ASC
        ");

        $permissions = $stmt->fetchAll();

        return array_values(array_filter($permissions, function (array $permission) use ($routeCodes): bool {
            return !in_array((string) $permission['code'], $routeCodes, true);
        }));
    }

    public function rolesWithoutPermission(): array
    {
        $stmt = $this->db->query("
            SELECT r.id, r.name, r.code, r.is_active
            FROM roles r
            LEFT JOIN role_permissions rp ON rp.role_id = r.id
            WHERE rp.id IS NULL
            ORDER BY r.name ASC
        ");

        return $stmt->fetchAll();
    }

    public function usersWithoutRole(): array
    {
        $stmt = $this->db->query("
            SELECT u.id, u.name, u.username, u.email, u.is_active
            FROM users u
            LEFT JOIN user_roles ur ON ur.user_id = u.id
            WHERE ur.id IS NULL
            ORDER BY u.name ASC
        ");

        return $stmt->fetchAll();
    }

    public function inactiveRolesStillAssigned(): array
    {
        $stmt = $this->db->query("
            SELECT 
                r.id,
                r.name,
                r.code,
                COUNT(ur.user_id) AS total_users
            FROM roles r
            INNER JOIN user_roles ur ON ur.role_id = r.id
            WHERE r.is_active = 0
            GROUP BY r.id, r.name, r.code
            ORDER BY r.name ASC
        ");

        return $stmt->fetchAll();
    }

    public function inactivePermissionsStillAssigned(): array
    {
        $stmt = $this->db->query("
            SELECT
                p.id,
                p.name,
                p.code,
                p.module,
                COUNT(rp.role_id) AS total_roles
            FROM permissions p
            INNER JOIN role_permissions rp ON rp.permission_id = p.id
            WHERE p.is_active = 0
            GROUP BY p.id, p.name, p.code, p.module
            ORDER BY p.module ASC, p.name ASC
        ");

        return $stmt->fetchAll();
    }

    public function controllerActionsWithoutPermissionCheck(): array
    {
        $mappedRoutes = $this->mappedControllerRoutes();
        $results = [];

        foreach ($mappedRoutes as $item) {
            if (empty($item['actual_permissions'])) {
                $results[] = [
                    'method' => $item['method'],
                    'uri' => $item['uri'],
                    'controller' => $item['controller'],
                    'action' => $item['action'],
                    'note' => 'Controller method kemungkinan belum melakukan permission check.',
                ];
            }
        }

        return $results;
    }

    public function permissionMismatchInControllers(): array
    {
        $mappedRoutes = $this->mappedControllerRoutes();
        $results = [];

        foreach ($mappedRoutes as $item) {
            if (empty($item['actual_permissions'])) {
                continue;
            }

            if (!in_array($item['expected_permission'], $item['actual_permissions'], true)) {
                $results[] = [
                    'method' => $item['method'],
                    'uri' => $item['uri'],
                    'controller' => $item['controller'],
                    'action' => $item['action'],
                    'expected_permission' => $item['expected_permission'],
                    'actual_permissions' => implode(', ', $item['actual_permissions']),
                ];
            }
        }

        return $results;
    }

    public function controllerPermissionNotRegistered(): array
    {
        $mappedRoutes = $this->mappedControllerRoutes();
        $registeredCodes = $this->registeredPermissionCodes();
        $results = [];
        $seen = [];

        foreach ($mappedRoutes as $item) {
            foreach ($item['actual_permissions'] as $permission) {
                $key = $item['controller'] . '|' . $item['action'] . '|' . $permission;

                if (!in_array($permission, $registeredCodes, true) && !isset($seen[$key])) {
                    $results[] = [
                        'method' => $item['method'],
                        'uri' => $item['uri'],
                        'controller' => $item['controller'],
                        'action' => $item['action'],
                        'permission' => $permission,
                        'module' => explode('.', $permission)[0] ?? 'general',
                        'name' => $this->humanizePermissionName($permission),
                    ];
                    $seen[$key] = true;
                }
            }
        }

        return $results;
    }

    public function registeredButUnusedPermissions(): array
    {
        $mappedRoutes = $this->mappedControllerRoutes();
        $usedPermissions = [];

        foreach ($mappedRoutes as $item) {
            foreach ($item['actual_permissions'] as $permission) {
                $usedPermissions[] = $permission;
            }
        }

        $usedPermissions = array_unique($usedPermissions);

        $stmt = $this->db->query("
            SELECT id, name, code, module, is_active
            FROM permissions
            ORDER BY module ASC, code ASC
        ");

        $permissions = $stmt->fetchAll();

        return array_values(array_filter($permissions, function (array $permission) use ($usedPermissions): bool {
            return !in_array((string) $permission['code'], $usedPermissions, true);
        }));
    }

    public function autoGenerateFromRoutes(): array
    {
        $rows = $this->routesWithoutPermission();
        $created = 0;
        $skipped = 0;

        try {
            $this->db->beginTransaction();

            foreach ($rows as $row) {
                if ($this->insertPermissionIfNotExists(
                    $row['suggested_name'],
                    $row['suggested_code'],
                    $row['module'],
                    $row['description']
                )) {
                    $created++;
                } else {
                    $skipped++;
                }
            }

            $this->db->commit();
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            return ['created' => 0, 'skipped' => count($rows)];
        }

        return ['created' => $created, 'skipped' => $skipped];
    }

    public function autoGenerateFromControllerUsage(): array
    {
        $rows = $this->controllerPermissionNotRegistered();
        $created = 0;
        $skipped = 0;

        try {
            $this->db->beginTransaction();

            foreach ($rows as $row) {
                if ($this->insertPermissionIfNotExists(
                    $row['name'],
                    $row['permission'],
                    $row['module'],
                    'Generated from controller permission usage audit.'
                )) {
                    $created++;
                } else {
                    $skipped++;
                }
            }

            $this->db->commit();
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            return ['created' => 0, 'skipped' => count($rows)];
        }

        return ['created' => $created, 'skipped' => $skipped];
    }

    private function insertPermissionIfNotExists(string $name, string $code, string $module, string $description): bool
    {
        $stmt = $this->db->prepare("
            SELECT id
            FROM permissions
            WHERE code = :code
            LIMIT 1
        ");
        $stmt->execute(['code' => $code]);

        if ($stmt->fetch()) {
            return false;
        }

        $insert = $this->db->prepare("
            INSERT INTO permissions (name, code, module, description, is_active)
            VALUES (:name, :code, :module, :description, 1)
        ");

        return $insert->execute([
            'name' => $name,
            'code' => $code,
            'module' => $module,
            'description' => $description,
        ]);
    }

    private function mappedControllerRoutes(): array
    {
        $routes = require ROUTES_PATH . '/web.php';
        $ignoredRoutes = $this->ignoredRoutes();
        $results = [];

        foreach ($routes as $route) {
            if (!isset($route[0], $route[1], $route[2])) {
                continue;
            }

            $method = (string) $route[0];
            $uri = (string) $route[1];
            $action = $route[2];

            $routeKey = strtoupper($method) . ' ' . $uri;
            if (in_array($routeKey, $ignoredRoutes, true)) {
                continue;
            }

            if (!is_array($action) || count($action) !== 2) {
                continue;
            }

            [$controllerClass, $controllerMethod] = $action;

            if (!is_string($controllerClass) || !is_string($controllerMethod)) {
                continue;
            }

            $controllerShortName = str_replace('App\\Controllers\\', '', $controllerClass);
            $controllerPath = APP_PATH . '/Controllers/' . str_replace('\\', '/', $controllerShortName) . '.php';

            if (!file_exists($controllerPath)) {
                continue;
            }

            $content = file_get_contents($controllerPath);
            if ($content === false) {
                continue;
            }

            $methodBody = $this->extractMethodBlock($content, $controllerMethod);
            if ($methodBody === '') {
                continue;
            }

            $normalized = trim($uri, '/');
            $segments = explode('/', $normalized);
            $module = str_replace('-', '_', $segments[0] ?? 'general');
            $expectedPermission = $this->guessPermissionCode($method, $uri, $module);
            $actualPermissions = $this->extractPermissionsFromMethod($methodBody);

            $results[] = [
                'method' => strtoupper($method),
                'uri' => $uri,
                'controller' => $controllerShortName,
                'action' => $controllerMethod,
                'expected_permission' => $expectedPermission,
                'actual_permissions' => $actualPermissions,
            ];
        }

        return $results;
    }

    private function extractMethodBlock(string $content, string $methodName): string
    {
        $needle = 'function ' . $methodName . '(';
        $pos = strpos($content, $needle);

        if ($pos === false) {
            return '';
        }

        $slice = substr($content, $pos, 5000);

        return $slice === false ? '' : $slice;
    }

    private function extractPermissionsFromMethod(string $methodBody): array
    {
        $matches = [];

        preg_match_all("/Auth::can\\(['\"]([^'\"]+)['\"]\\)/", $methodBody, $authCanMatches);
        preg_match_all("/(?<![a-zA-Z0-9_])can\\(['\"]([^'\"]+)['\"]\\)/", $methodBody, $helperCanMatches);

        foreach ($authCanMatches[1] ?? [] as $item) {
            $matches[] = $item;
        }

        foreach ($helperCanMatches[1] ?? [] as $item) {
            $matches[] = $item;
        }

        return array_values(array_unique($matches));
    }

    private function registeredPermissionCodes(): array
    {
        $stmt = $this->db->query("SELECT code FROM permissions");
        return array_map(static fn(array $row): string => (string) $row['code'], $stmt->fetchAll());
    }

    private function ignoredRoutes(): array
    {
        return [
            'GET /',
            'GET /login',
            'POST /login',
            'POST /logout',
        ];
    }

    private function guessPermissionCode(string $method, string $uri, string $module): string
    {
        $method = strtoupper($method);
        $normalized = trim($uri, '/');

        if ($normalized === 'dashboard') {
            return 'dashboard.view';
        }

        if ($normalized === 'rbac/health') {
            return 'rbac.health.view';
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

    private function humanizePermissionName(string $permission): string
    {
        $parts = explode('.', $permission);
        $action = $parts[1] ?? 'view';
        $module = $parts[0] ?? 'general';

        return ucfirst($action) . ' ' . ucwords(str_replace('_', ' ', $module));
    }
}