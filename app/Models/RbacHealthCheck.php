<?php

declare(strict_types=1);

namespace App\Models;

use Core\Model;

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
        $unprotectedControllerActions = count($this->controllerActionsWithoutPermissionCheck());

        return [
            'routes_without_permission' => $routesWithoutPermission,
            'permissions_without_route' => $permissionsWithoutRoute,
            'roles_without_permission' => $rolesWithoutPermission,
            'users_without_role' => $usersWithoutRole,
            'inactive_roles_used' => $inactiveRolesUsed,
            'inactive_permissions_used' => $inactivePermissionsUsed,
            'controller_actions_without_permission_check' => $unprotectedControllerActions,
            'total_issues' => $routesWithoutPermission
                + $permissionsWithoutRoute
                + $rolesWithoutPermission
                + $usersWithoutRole
                + $inactiveRolesUsed
                + $inactivePermissionsUsed
                + $unprotectedControllerActions,
        ];
    }

    public function routesWithoutPermission(): array
    {
        $routes = require ROUTES_PATH . '/web.php';

        $permissionModel = new Permission();
        $permissions = $permissionModel->all();
        $permissionCodes = array_map(static fn(array $item): string => (string) $item['code'], $permissions);

        $ignoredRoutes = $this->ignoredRoutes();

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
            $suggestedCode = $this->guessPermissionCode($method, $uri, $module);

            if (!in_array($suggestedCode, $permissionCodes, true)) {
                $results[] = [
                    'method' => strtoupper($method),
                    'uri' => $uri,
                    'module' => $module,
                    'suggested_code' => $suggestedCode,
                    'suggested_name' => $this->guessPermissionName($method, $uri, $module),
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
            [$method, $uri] = $route;

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
            return !in_array($permission['code'], $routeCodes, true);
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
        $routes = require ROUTES_PATH . '/web.php';
        $ignoredRoutes = $this->ignoredRoutes();
        $results = [];

        foreach ($routes as $route) {
            [$method, $uri, $action] = $route;

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

            $methodBody = $this->extractMethodBody($content, $controllerMethod);

            if ($methodBody === null) {
                continue;
            }

            $hasPermissionCheck =
                str_contains($methodBody, 'Auth::can(') ||
                str_contains($methodBody, "can('") ||
                str_contains($methodBody, 'can("');

            if (!$hasPermissionCheck) {
                $results[] = [
                    'method' => strtoupper($method),
                    'uri' => $uri,
                    'controller' => $controllerShortName,
                    'action' => $controllerMethod,
                    'note' => 'Controller method kemungkinan belum melakukan permission check.',
                ];
            }
        }

        return $results;
    }

    private function extractMethodBody(string $content, string $methodName): ?string
    {
        $pattern = '/function\s+' . preg_quote($methodName, '/') . '\s*\([^)]*\)\s*:\s*void\s*\{([\s\S]*?)\n\s*\}/';

        if (preg_match($pattern, $content, $matches)) {
            return $matches[1] ?? null;
        }

        $patternNoReturnType = '/function\s+' . preg_quote($methodName, '/') . '\s*\([^)]*\)\s*\{([\s\S]*?)\n\s*\}/';

        if (preg_match($patternNoReturnType, $content, $matches)) {
            return $matches[1] ?? null;
        }

        return null;
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
}