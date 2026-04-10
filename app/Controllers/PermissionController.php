<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Permission;
use App\Models\RoutePermissionAudit;
use Core\Auth;
use Core\Controller;

require_once APP_PATH . '/Models/Permission.php';
require_once APP_PATH . '/Models/RoutePermissionAudit.php';

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

        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = (int) ($_GET['per_page'] ?? 5);

        $permissionModel = new Permission();
        $pagination = $permissionModel->paginate($page, $perPage);

        $this->view('permissions/index', [
            'title' => 'Permissions',
            'permissions' => $pagination['data'],
            'pagination' => $pagination,
        ]);
    }

    public function create(): void
    {
        if (!Auth::check()) {
            flash('error', 'Silakan login terlebih dahulu.');
            redirect('login');
        }

        if (!Auth::can('permissions.create')) {
            http_response_code(403);
            $this->view('errors/403', ['title' => '403 Forbidden']);
            return;
        }

        $auditModel = new RoutePermissionAudit();

        $suggested = [
            'name' => $_GET['suggest_name'] ?? '',
            'code' => $_GET['suggest_code'] ?? '',
            'module' => $_GET['suggest_module'] ?? '',
            'description' => $_GET['suggest_description'] ?? '',
        ];

        $this->view('permissions/create', [
            'title' => 'Create Permission',
            'undocumentedRoutes' => $auditModel->getUndocumentedRoutes(),
            'suggested' => $suggested,
        ]);
    }

    public function store(): void
    {
        if (!Auth::check()) {
            flash('error', 'Silakan login terlebih dahulu.');
            redirect('login');
        }

        if (!Auth::can('permissions.create')) {
            http_response_code(403);
            $this->view('errors/403', ['title' => '403 Forbidden']);
            return;
        }

        if (!verify_csrf()) {
            flash('error', 'Token CSRF tidak valid.');
            redirect('permissions/create');
        }

        $name = trim($_POST['name'] ?? '');
        $code = trim($_POST['code'] ?? '');
        $module = trim($_POST['module'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        $_SESSION['_old'] = [
            'name' => $name,
            'code' => $code,
            'module' => $module,
            'description' => $description,
            'is_active' => $isActive,
        ];

        if ($name === '' || $code === '' || $module === '') {
            flash('error', 'Name, code, dan module wajib diisi.');
            redirect('permissions/create');
        }

        $permissionModel = new Permission();

        if ($permissionModel->codeExists($code)) {
            flash('error', 'Code permission sudah digunakan.');
            redirect('permissions/create');
        }

        $created = $permissionModel->create([
            'name' => $name,
            'code' => $code,
            'module' => $module,
            'description' => $description,
            'is_active' => $isActive,
        ]);

        if (!$created) {
            flash('error', 'Gagal menambahkan permission.');
            redirect('permissions/create');
        }

        unset($_SESSION['_old']);
        flash('success', 'Permission berhasil ditambahkan.');
        redirect('permissions');
    }

    public function autoStore(): void
    {
        if (!Auth::check()) {
            flash('error', 'Silakan login terlebih dahulu.');
            redirect('login');
        }

        if (!Auth::can('permissions.create')) {
            http_response_code(403);
            $this->view('errors/403', ['title' => '403 Forbidden']);
            return;
        }

        if (!verify_csrf()) {
            flash('error', 'Token CSRF tidak valid.');
            redirect('permissions/create');
        }

        $name = trim($_POST['name'] ?? '');
        $code = trim($_POST['code'] ?? '');
        $module = trim($_POST['module'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if ($name === '' || $code === '' || $module === '') {
            flash('error', 'Data auto-generate tidak lengkap.');
            redirect('permissions/create');
        }

        $auditModel = new RoutePermissionAudit();
        $created = $auditModel->generatePermission($name, $code, $module, $description);

        if (!$created) {
            flash('error', 'Permission gagal dibuat atau sudah ada.');
            redirect('permissions/create');
        }

        flash('success', 'Permission berhasil dibuat dari route audit.');
        redirect('permissions');
    }

    public function autoStoreAll(): void
    {
        if (!Auth::check()) {
            flash('error', 'Silakan login terlebih dahulu.');
            redirect('login');
        }

        if (!Auth::can('permissions.create')) {
            http_response_code(403);
            $this->view('errors/403', ['title' => '403 Forbidden']);
            return;
        }

        if (!verify_csrf()) {
            flash('error', 'Token CSRF tidak valid.');
            redirect('permissions/create');
        }

        $auditModel = new RoutePermissionAudit();
        $result = $auditModel->generateAllMissingPermissions();

        flash(
            'success',
            'Auto-generate selesai. Created: ' . $result['created'] . ', Skipped: ' . $result['skipped'] . '.'
        );

        redirect('permissions');
    }

    public function edit(): void
    {
        if (!Auth::check()) {
            flash('error', 'Silakan login terlebih dahulu.');
            redirect('login');
        }

        if (!Auth::can('permissions.edit')) {
            http_response_code(403);
            $this->view('errors/403', ['title' => '403 Forbidden']);
            return;
        }

        $id = (int) ($_GET['id'] ?? 0);

        $permissionModel = new Permission();
        $permission = $permissionModel->findById($id);

        if (!$permission) {
            http_response_code(404);
            $this->view('errors/404', ['title' => '404 Not Found']);
            return;
        }

        $this->view('permissions/edit', [
            'title' => 'Edit Permission',
            'permissionData' => $permission,
        ]);
    }

    public function update(): void
    {
        if (!Auth::check()) {
            flash('error', 'Silakan login terlebih dahulu.');
            redirect('login');
        }

        if (!Auth::can('permissions.edit')) {
            http_response_code(403);
            $this->view('errors/403', ['title' => '403 Forbidden']);
            return;
        }

        if (!verify_csrf()) {
            flash('error', 'Token CSRF tidak valid.');
            redirect('permissions');
        }

        $id = (int) ($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $code = trim($_POST['code'] ?? '');
        $module = trim($_POST['module'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        $_SESSION['_old'] = [
            'name' => $name,
            'code' => $code,
            'module' => $module,
            'description' => $description,
            'is_active' => $isActive,
        ];

        if ($name === '' || $code === '' || $module === '') {
            flash('error', 'Name, code, dan module wajib diisi.');
            redirect('permissions/edit?id=' . $id);
        }

        $permissionModel = new Permission();

        if ($permissionModel->codeExists($code, $id)) {
            flash('error', 'Code permission sudah digunakan.');
            redirect('permissions/edit?id=' . $id);
        }

        $updated = $permissionModel->update($id, [
            'name' => $name,
            'code' => $code,
            'module' => $module,
            'description' => $description,
            'is_active' => $isActive,
        ]);

        if (!$updated) {
            flash('error', 'Gagal mengupdate permission.');
            redirect('permissions/edit?id=' . $id);
        }

        unset($_SESSION['_old']);
        flash('success', 'Permission berhasil diupdate.');
        redirect('permissions');
    }
}