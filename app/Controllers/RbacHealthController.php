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

        $permissionRoutePage = max(1, (int) ($_GET['permission_route_page'] ?? 1));
        $permissionRoutePerPage = (int) ($_GET['permission_route_per_page'] ?? 5);

        $controllerCheckPage = max(1, (int) ($_GET['controller_check_page'] ?? 1));
        $controllerCheckPerPage = (int) ($_GET['controller_check_per_page'] ?? 5);

        $mismatchPage = max(1, (int) ($_GET['mismatch_page'] ?? 1));
        $mismatchPerPage = (int) ($_GET['mismatch_per_page'] ?? 5);

        $controllerMissingPage = max(1, (int) ($_GET['controller_missing_page'] ?? 1));
        $controllerMissingPerPage = (int) ($_GET['controller_missing_per_page'] ?? 5);

        $unusedPermissionPage = max(1, (int) ($_GET['unused_permission_page'] ?? 1));
        $unusedPermissionPerPage = (int) ($_GET['unused_permission_per_page'] ?? 5);

        $roleNoPermissionPage = max(1, (int) ($_GET['role_no_permission_page'] ?? 1));
        $roleNoPermissionPerPage = (int) ($_GET['role_no_permission_per_page'] ?? 5);

        $userNoRolePage = max(1, (int) ($_GET['user_no_role_page'] ?? 1));
        $userNoRolePerPage = (int) ($_GET['user_no_role_per_page'] ?? 5);

        $inactiveRolePage = max(1, (int) ($_GET['inactive_role_page'] ?? 1));
        $inactiveRolePerPage = (int) ($_GET['inactive_role_per_page'] ?? 5);

        $inactivePermissionPage = max(1, (int) ($_GET['inactive_permission_page'] ?? 1));
        $inactivePermissionPerPage = (int) ($_GET['inactive_permission_per_page'] ?? 5);

        $this->view('rbac/health', [
            'title' => 'RBAC Health Check',
            'summary' => $health->summary(),

            'routesWithoutPermissionPagination' => $health->paginateArray($health->routesWithoutPermission(), $routePage, $routePerPage),
            'permissionsWithoutRoutePagination' => $health->paginateArray($health->permissionsWithoutRoute(), $permissionRoutePage, $permissionRoutePerPage),
            'controllerActionsWithoutPermissionCheckPagination' => $health->paginateArray($health->controllerActionsWithoutPermissionCheck(), $controllerCheckPage, $controllerCheckPerPage),
            'permissionMismatchInControllersPagination' => $health->paginateArray($health->permissionMismatchInControllers(), $mismatchPage, $mismatchPerPage),
            'controllerPermissionNotRegisteredPagination' => $health->paginateArray($health->controllerPermissionNotRegistered(), $controllerMissingPage, $controllerMissingPerPage),
            'registeredButUnusedPermissionsPagination' => $health->paginateArray($health->registeredButUnusedPermissions(), $unusedPermissionPage, $unusedPermissionPerPage),
            'rolesWithoutPermissionPagination' => $health->paginateArray($health->rolesWithoutPermission(), $roleNoPermissionPage, $roleNoPermissionPerPage),
            'usersWithoutRolePagination' => $health->paginateArray($health->usersWithoutRole(), $userNoRolePage, $userNoRolePerPage),
            'inactiveRolesUsedPagination' => $health->paginateArray($health->inactiveRolesStillAssigned(), $inactiveRolePage, $inactiveRolePerPage),
            'inactivePermissionsUsedPagination' => $health->paginateArray($health->inactivePermissionsStillAssigned(), $inactivePermissionPage, $inactivePermissionPerPage),
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