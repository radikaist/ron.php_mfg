<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Permission;
use Core\Auth;
use Core\Controller;

require_once APP_PATH . '/Models/Permission.php';

class PermissionController extends Controller
{
    public function index(): void
    {
        if (!Auth::check()) {
            flash('error', 'Silakan login terlebih dahulu.');
            redirect('login');
        }

        if (!Auth::can('permissions.view')) {
            http_response_code(403);
            $this->view('errors/403', ['title' => '403 Forbidden']);
            return;
        }

        $permissionModel = new Permission();

        $this->view('permissions/index', [
            'title' => 'Permissions',
            'permissions' => $permissionModel->all(),
        ]);
    }
}