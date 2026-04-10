<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Role;
use Core\Auth;
use Core\Controller;

require_once APP_PATH . '/Models/Role.php';

class RoleController extends Controller
{
    public function index(): void
    {
        if (!Auth::check()) {
            flash('error', 'Silakan login terlebih dahulu.');
            redirect('login');
        }

        if (!Auth::can('roles.view')) {
            http_response_code(403);
            $this->view('errors/403', ['title' => '403 Forbidden']);
            return;
        }

        $roleModel = new Role();

        $this->view('roles/index', [
            'title' => 'Roles',
            'roles' => $roleModel->all(),
        ]);
    }
}