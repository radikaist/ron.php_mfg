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
        ]);
    }
}