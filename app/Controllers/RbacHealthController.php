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

        $this->view('rbac/health', [
            'title' => 'RBAC Health Check',
            'summary' => $health->summary(),
            'routesWithoutPermission' => $health->routesWithoutPermission(),
            'permissionsWithoutRoute' => $health->permissionsWithoutRoute(),
            'rolesWithoutPermission' => $health->rolesWithoutPermission(),
            'usersWithoutRole' => $health->usersWithoutRole(),
            'inactiveRolesUsed' => $health->inactiveRolesStillAssigned(),
            'inactivePermissionsUsed' => $health->inactivePermissionsStillAssigned(),
            'controllerActionsWithoutPermissionCheck' => $health->controllerActionsWithoutPermissionCheck(),
            'permissionMismatchInControllers' => $health->permissionMismatchInControllers(),
            'controllerPermissionNotRegistered' => $health->controllerPermissionNotRegistered(),
            'registeredButUnusedPermissions' => $health->registeredButUnusedPermissions(),
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