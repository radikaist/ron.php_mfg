<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Role;
use App\Models\User;
use Core\Auth;
use Core\Controller;

require_once APP_PATH . '/Models/User.php';
require_once APP_PATH . '/Models/Role.php';

class UserController extends Controller
{
    public function index(): void
    {
        if (!Auth::check()) {
            flash('error', 'Silakan login terlebih dahulu.');
            redirect('login');
        }

        if (!Auth::can('users.view')) {
            http_response_code(403);
            $this->view('errors/403', ['title' => '403 Forbidden']);
            return;
        }

        $userModel = new User();
        $users = $userModel->all();

        $this->view('users/index', [
            'title' => 'Users',
            'users' => $users,
        ]);
    }

    public function create(): void
    {
        if (!Auth::check()) {
            flash('error', 'Silakan login terlebih dahulu.');
            redirect('login');
        }

        if (!Auth::can('users.create')) {
            http_response_code(403);
            $this->view('errors/403', ['title' => '403 Forbidden']);
            return;
        }

        $roleModel = new Role();

        $this->view('users/create', [
            'title' => 'Create User',
            'roles' => $roleModel->activeOptions(),
        ]);
    }

    public function store(): void
    {
        if (!Auth::check()) {
            flash('error', 'Silakan login terlebih dahulu.');
            redirect('login');
        }

        if (!Auth::can('users.create')) {
            http_response_code(403);
            $this->view('errors/403', ['title' => '403 Forbidden']);
            return;
        }

        if (!verify_csrf()) {
            flash('error', 'Token CSRF tidak valid.');
            redirect('users/create');
        }

        $name = trim($_POST['name'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirmation'] ?? '';
        $roleIds = $_POST['role_ids'] ?? [];
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        $_SESSION['_old'] = [
            'name' => $name,
            'username' => $username,
            'email' => $email,
            'role_ids' => $roleIds,
            'is_active' => $isActive,
        ];

        if ($name === '' || $username === '' || $password === '') {
            flash('error', 'Name, username, dan password wajib diisi.');
            redirect('users/create');
        }

        if ($password !== $passwordConfirm) {
            flash('error', 'Konfirmasi password tidak sama.');
            redirect('users/create');
        }

        $userModel = new User();

        if ($userModel->usernameExists($username)) {
            flash('error', 'Username sudah digunakan.');
            redirect('users/create');
        }

        $created = $userModel->create([
            'name' => $name,
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'role_ids' => $roleIds,
            'is_active' => $isActive,
        ]);

        if (!$created) {
            flash('error', 'Gagal menambahkan user.');
            redirect('users/create');
        }

        unset($_SESSION['_old']);
        flash('success', 'User berhasil ditambahkan.');
        redirect('users');
    }

    public function edit(): void
    {
        if (!Auth::check()) {
            flash('error', 'Silakan login terlebih dahulu.');
            redirect('login');
        }

        if (!Auth::can('users.edit')) {
            http_response_code(403);
            $this->view('errors/403', ['title' => '403 Forbidden']);
            return;
        }

        $id = (int) ($_GET['id'] ?? 0);

        $userModel = new User();
        $roleModel = new Role();

        $user = $userModel->findById($id);

        if (!$user) {
            http_response_code(404);
            $this->view('errors/404', ['title' => '404 Not Found']);
            return;
        }

        $user['role_ids'] = $userModel->getRoleIds($id);

        $this->view('users/edit', [
            'title' => 'Edit User',
            'userData' => $user,
            'roles' => $roleModel->activeOptions(),
        ]);
    }

    public function update(): void
    {
        if (!Auth::check()) {
            flash('error', 'Silakan login terlebih dahulu.');
            redirect('login');
        }

        if (!Auth::can('users.edit')) {
            http_response_code(403);
            $this->view('errors/403', ['title' => '403 Forbidden']);
            return;
        }

        if (!verify_csrf()) {
            flash('error', 'Token CSRF tidak valid.');
            redirect('users');
        }

        $id = (int) ($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirmation'] ?? '';
        $roleIds = $_POST['role_ids'] ?? [];
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        if ($name === '' || $username === '') {
            flash('error', 'Name dan username wajib diisi.');
            redirect('users/edit?id=' . $id);
        }

        if ($password !== '' && $password !== $passwordConfirm) {
            flash('error', 'Konfirmasi password tidak sama.');
            redirect('users/edit?id=' . $id);
        }

        $userModel = new User();

        if ($userModel->usernameExists($username, $id)) {
            flash('error', 'Username sudah digunakan.');
            redirect('users/edit?id=' . $id);
        }

        $updated = $userModel->update($id, [
            'name' => $name,
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'role_ids' => $roleIds,
            'is_active' => $isActive,
        ]);

        if (!$updated) {
            flash('error', 'Gagal mengupdate user.');
            redirect('users/edit?id=' . $id);
        }

        flash('success', 'User berhasil diupdate.');
        redirect('users');
    }
}