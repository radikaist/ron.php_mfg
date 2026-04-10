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
        $roles = $roleModel->activeOptions();

        $this->view('users/create', [
            'title' => 'Create User',
            'roles' => $roles,
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
        $existing = $userModel->findByUsername($username);

        if ($existing) {
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
}