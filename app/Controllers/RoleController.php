<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Core\Auth;
use Core\Controller;

require_once APP_PATH . '/Models/Role.php';
require_once APP_PATH . '/Models/Permission.php';

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

        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = (int) ($_GET['per_page'] ?? 5);
        $search = trim($_GET['search'] ?? '');

        $roleModel = new Role();
        $pagination = $roleModel->paginate($page, $perPage, $search);

        $this->view('roles/index', [
            'title' => 'Roles',
            'roles' => $pagination['data'],
            'pagination' => $pagination,
        ]);
    }

    public function create(): void
    {
        if (!Auth::check()) {
            flash('error', 'Silakan login terlebih dahulu.');
            redirect('login');
        }

        if (!Auth::can('roles.create')) {
            http_response_code(403);
            $this->view('errors/403', ['title' => '403 Forbidden']);
            return;
        }

        $permissionModel = new Permission();

        $this->view('roles/create', [
            'title' => 'Create Role',
            'permissions' => $permissionModel->activeOptions(),
        ]);
    }

    public function store(): void
    {
        if (!Auth::check()) {
            flash('error', 'Silakan login terlebih dahulu.');
            redirect('login');
        }

        if (!Auth::can('roles.create')) {
            http_response_code(403);
            $this->view('errors/403', ['title' => '403 Forbidden']);
            return;
        }

        if (!verify_csrf()) {
            flash('error', 'Token CSRF tidak valid.');
            redirect('roles/create');
        }

        $name = trim($_POST['name'] ?? '');
        $code = trim($_POST['code'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $permissionIds = $_POST['permission_ids'] ?? [];
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        $_SESSION['_old'] = [
            'name' => $name,
            'code' => $code,
            'description' => $description,
            'permission_ids' => $permissionIds,
            'is_active' => $isActive,
        ];

        if ($name === '' || $code === '') {
            flash('error', 'Name dan code role wajib diisi.');
            redirect('roles/create');
        }

        $roleModel = new Role();

        if ($roleModel->codeExists($code)) {
            flash('error', 'Code role sudah digunakan.');
            redirect('roles/create');
        }

        $created = $roleModel->create([
            'name' => $name,
            'code' => $code,
            'description' => $description,
            'permission_ids' => $permissionIds,
            'is_active' => $isActive,
        ]);

        if (!$created) {
            flash('error', 'Gagal menambahkan role.');
            redirect('roles/create');
        }

        unset($_SESSION['_old']);
        flash('success', 'Role berhasil ditambahkan.');
        redirect('roles');
    }

    public function edit(): void
    {
        if (!Auth::check()) {
            flash('error', 'Silakan login terlebih dahulu.');
            redirect('login');
        }

        if (!Auth::can('roles.edit')) {
            http_response_code(403);
            $this->view('errors/403', ['title' => '403 Forbidden']);
            return;
        }

        $id = (int) ($_GET['id'] ?? 0);

        $roleModel = new Role();
        $permissionModel = new Permission();

        $role = $roleModel->findById($id);

        if (!$role) {
            http_response_code(404);
            $this->view('errors/404', ['title' => '404 Not Found']);
            return;
        }

        $role['permission_ids'] = $roleModel->getPermissionIds($id);

        $this->view('roles/edit', [
            'title' => 'Edit Role',
            'roleData' => $role,
            'permissions' => $permissionModel->activeOptions(),
        ]);
    }

    public function update(): void
    {
        if (!Auth::check()) {
            flash('error', 'Silakan login terlebih dahulu.');
            redirect('login');
        }

        if (!Auth::can('roles.edit')) {
            http_response_code(403);
            $this->view('errors/403', ['title' => '403 Forbidden']);
            return;
        }

        if (!verify_csrf()) {
            flash('error', 'Token CSRF tidak valid.');
            redirect('roles');
        }

        $id = (int) ($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $code = trim($_POST['code'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $permissionIds = $_POST['permission_ids'] ?? [];
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        $_SESSION['_old'] = [
            'name' => $name,
            'code' => $code,
            'description' => $description,
            'permission_ids' => $permissionIds,
            'is_active' => $isActive,
        ];

        if ($name === '' || $code === '') {
            flash('error', 'Name dan code role wajib diisi.');
            redirect('roles/edit?id=' . $id);
        }

        $roleModel = new Role();

        if ($roleModel->codeExists($code, $id)) {
            flash('error', 'Code role sudah digunakan.');
            redirect('roles/edit?id=' . $id);
        }

        $updated = $roleModel->update($id, [
            'name' => $name,
            'code' => $code,
            'description' => $description,
            'permission_ids' => $permissionIds,
            'is_active' => $isActive,
        ]);

        if (!$updated) {
            flash('error', 'Gagal mengupdate role.');
            redirect('roles/edit?id=' . $id);
        }

        unset($_SESSION['_old']);
        flash('success', 'Role berhasil diupdate.');
        redirect('roles');
    }
}