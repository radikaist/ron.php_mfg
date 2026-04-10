<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\RbacHealthCheck;
use Core\Auth;
use Core\Controller;

require_once APP_PATH . '/Models/RbacHealthCheck.php';

class RbacHealthController extends Controller
{
    public function index(): void
    {
        if (!Auth::check()) {
            flash('error', 'Silakan login terlebih dahulu.');
            redirect('login');
        }

        if (!Auth::can('rbac.health.view')) {
            http_response_code(403);
            $this->view('errors/403', ['title' => '403 Forbidden']);
            return;
        }

        $health = new RbacHealthCheck();

        $routePage = max(1, (int) ($_GET['route_page'] ?? 1));
        $routePerPage = (int) ($_GET['route_per_page'] ?? 5);
        $routeSearch = trim($_GET['route_search'] ?? '');

        $permissionRoutePage = max(1, (int) ($_GET['permission_route_page'] ?? 1));
        $permissionRoutePerPage = (int) ($_GET['permission_route_per_page'] ?? 5);
        $permissionRouteSearch = trim($_GET['permission_route_search'] ?? '');

        $controllerCheckPage = max(1, (int) ($_GET['controller_check_page'] ?? 1));
        $controllerCheckPerPage = (int) ($_GET['controller_check_per_page'] ?? 5);
        $controllerCheckSearch = trim($_GET['controller_check_search'] ?? '');

        $mismatchPage = max(1, (int) ($_GET['mismatch_page'] ?? 1));
        $mismatchPerPage = (int) ($_GET['mismatch_per_page'] ?? 5);
        $mismatchSearch = trim($_GET['mismatch_search'] ?? '');

        $controllerMissingPage = max(1, (int) ($_GET['controller_missing_page'] ?? 1));
        $controllerMissingPerPage = (int) ($_GET['controller_missing_per_page'] ?? 5);
        $controllerMissingSearch = trim($_GET['controller_missing_search'] ?? '');

        $unusedPermissionPage = max(1, (int) ($_GET['unused_permission_page'] ?? 1));
        $unusedPermissionPerPage = (int) ($_GET['unused_permission_per_page'] ?? 5);
        $unusedPermissionSearch = trim($_GET['unused_permission_search'] ?? '');

        $roleNoPermissionPage = max(1, (int) ($_GET['role_no_permission_page'] ?? 1));
        $roleNoPermissionPerPage = (int) ($_GET['role_no_permission_per_page'] ?? 5);
        $roleNoPermissionSearch = trim($_GET['role_no_permission_search'] ?? '');

        $userNoRolePage = max(1, (int) ($_GET['user_no_role_page'] ?? 1));
        $userNoRolePerPage = (int) ($_GET['user_no_role_per_page'] ?? 5);
        $userNoRoleSearch = trim($_GET['user_no_role_search'] ?? '');

        $inactiveRolePage = max(1, (int) ($_GET['inactive_role_page'] ?? 1));
        $inactiveRolePerPage = (int) ($_GET['inactive_role_per_page'] ?? 5);
        $inactiveRoleSearch = trim($_GET['inactive_role_search'] ?? '');

        $inactivePermissionPage = max(1, (int) ($_GET['inactive_permission_page'] ?? 1));
        $inactivePermissionPerPage = (int) ($_GET['inactive_permission_per_page'] ?? 5);
        $inactivePermissionSearch = trim($_GET['inactive_permission_search'] ?? '');

        $routesWithoutPermission = $health->filterArrayByKeyword($health->routesWithoutPermission(), $routeSearch);
        $permissionsWithoutRoute = $health->filterArrayByKeyword($health->permissionsWithoutRoute(), $permissionRouteSearch);
        $controllerActionsWithoutPermissionCheck = $health->filterArrayByKeyword($health->controllerActionsWithoutPermissionCheck(), $controllerCheckSearch);
        $permissionMismatchInControllers = $health->filterArrayByKeyword($health->permissionMismatchInControllers(), $mismatchSearch);
        $controllerPermissionNotRegistered = $health->filterArrayByKeyword($health->controllerPermissionNotRegistered(), $controllerMissingSearch);
        $registeredButUnusedPermissions = $health->filterArrayByKeyword($health->registeredButUnusedPermissions(), $unusedPermissionSearch);
        $rolesWithoutPermission = $health->filterArrayByKeyword($health->rolesWithoutPermission(), $roleNoPermissionSearch);
        $usersWithoutRole = $health->filterArrayByKeyword($health->usersWithoutRole(), $userNoRoleSearch);
        $inactiveRolesUsed = $health->filterArrayByKeyword($health->inactiveRolesStillAssigned(), $inactiveRoleSearch);
        $inactivePermissionsUsed = $health->filterArrayByKeyword($health->inactivePermissionsStillAssigned(), $inactivePermissionSearch);

        $this->view('rbac/health', [
            'title' => 'RBAC Health Check',
            'summary' => $health->summary(),

            'routesWithoutPermissionPagination' => $health->paginateArray($routesWithoutPermission, $routePage, $routePerPage),
            'permissionsWithoutRoutePagination' => $health->paginateArray($permissionsWithoutRoute, $permissionRoutePage, $permissionRoutePerPage),
            'controllerActionsWithoutPermissionCheckPagination' => $health->paginateArray($controllerActionsWithoutPermissionCheck, $controllerCheckPage, $controllerCheckPerPage),
            'permissionMismatchInControllersPagination' => $health->paginateArray($permissionMismatchInControllers, $mismatchPage, $mismatchPerPage),
            'controllerPermissionNotRegisteredPagination' => $health->paginateArray($controllerPermissionNotRegistered, $controllerMissingPage, $controllerMissingPerPage),
            'registeredButUnusedPermissionsPagination' => $health->paginateArray($registeredButUnusedPermissions, $unusedPermissionPage, $unusedPermissionPerPage),
            'rolesWithoutPermissionPagination' => $health->paginateArray($rolesWithoutPermission, $roleNoPermissionPage, $roleNoPermissionPerPage),
            'usersWithoutRolePagination' => $health->paginateArray($usersWithoutRole, $userNoRolePage, $userNoRolePerPage),
            'inactiveRolesUsedPagination' => $health->paginateArray($inactiveRolesUsed, $inactiveRolePage, $inactiveRolePerPage),
            'inactivePermissionsUsedPagination' => $health->paginateArray($inactivePermissionsUsed, $inactivePermissionPage, $inactivePermissionPerPage),

            'filters' => [
                'route_search' => $routeSearch,
                'permission_route_search' => $permissionRouteSearch,
                'controller_check_search' => $controllerCheckSearch,
                'mismatch_search' => $mismatchSearch,
                'controller_missing_search' => $controllerMissingSearch,
                'unused_permission_search' => $unusedPermissionSearch,
                'role_no_permission_search' => $roleNoPermissionSearch,
                'user_no_role_search' => $userNoRoleSearch,
                'inactive_role_search' => $inactiveRoleSearch,
                'inactive_permission_search' => $inactivePermissionSearch,
            ],
        ]);
    }

    public function autoGenerateRoutes(): void
    {
        if (!Auth::check()) {
            flash('error', 'Silakan login terlebih dahulu.');
            redirect('login');
        }

        if (!Auth::can('rbac.health.view')) {
            http_response_code(403);
            $this->view('errors/403', ['title' => '403 Forbidden']);
            return;
        }

        if (!verify_csrf()) {
            flash('error', 'Token CSRF tidak valid.');
            redirect('rbac/health');
        }

        $health = new RbacHealthCheck();
        $result = $health->autoGenerateFromRoutes();

        flash('success', 'Auto-generate dari route selesai. Created: ' . $result['created'] . ', Skipped: ' . $result['skipped'] . '.');
        redirect('rbac/health');
    }

    public function autoGenerateControllerPermissions(): void
    {
        if (!Auth::check()) {
            flash('error', 'Silakan login terlebih dahulu.');
            redirect('login');
        }

        if (!Auth::can('rbac.health.view')) {
            http_response_code(403);
            $this->view('errors/403', ['title' => '403 Forbidden']);
            return;
        }

        if (!verify_csrf()) {
            flash('error', 'Token CSRF tidak valid.');
            redirect('rbac/health');
        }

        $health = new RbacHealthCheck();
        $result = $health->autoGenerateFromControllerUsage();

        flash('success', 'Auto-generate dari controller usage selesai. Created: ' . $result['created'] . ', Skipped: ' . $result['skipped'] . '.');
        redirect('rbac/health');
    }

    public function exportCsv(): void
    {
        if (!Auth::check()) {
            flash('error', 'Silakan login terlebih dahulu.');
            redirect('login');
        }

        if (!Auth::can('rbac.health.view')) {
            http_response_code(403);
            $this->view('errors/403', ['title' => '403 Forbidden']);
            return;
        }

        $health = new RbacHealthCheck();

        $rows = [];

        foreach ($health->routesWithoutPermission() as $item) {
            $rows[] = ['routes_without_permission', $item['method'], $item['uri'], $item['suggested_code'], $item['suggested_name']];
        }

        foreach ($health->permissionMismatchInControllers() as $item) {
            $rows[] = ['permission_mismatch', $item['method'], $item['uri'], $item['expected_permission'], $item['actual_permissions']];
        }

        foreach ($health->controllerPermissionNotRegistered() as $item) {
            $rows[] = ['controller_permission_not_registered', $item['method'], $item['uri'], $item['permission'], $item['controller'] . '@' . $item['action']];
        }

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="rbac-health-report.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['category', 'method', 'uri', 'value_1', 'value_2']);

        foreach ($rows as $row) {
            fputcsv($output, $row);
        }

        fclose($output);
        exit;
    }
}