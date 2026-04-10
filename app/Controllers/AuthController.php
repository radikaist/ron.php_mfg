<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\User;
use Core\Auth;
use Core\Controller;

require_once APP_PATH . '/Models/User.php';

class AuthController extends Controller
{
    public function index(): void
    {
        if (Auth::check()) {
            redirect('dashboard');
        }

        $this->view('auth/login', [
            'title' => 'Login',
        ], 'layouts/guest');
    }

    public function login(): void
    {
        if (!verify_csrf()) {
            flash('error', 'Token CSRF tidak valid.');
            redirect('login');
        }

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        $_SESSION['_old'] = [
            'username' => $username,
        ];

        if ($username === '' || $password === '') {
            flash('error', 'Username dan password wajib diisi.');
            redirect('login');
        }

        $userModel = new User();
        $user = $userModel->findByUsername($username);

        if (!$user || !password_verify($password, $user['password'])) {
            flash('error', 'Username atau password salah.');
            redirect('login');
        }

        Auth::login($user);

        if (!Auth::check()) {
            flash('error', 'Gagal memuat data otorisasi user.');
            redirect('login');
        }

        unset($_SESSION['_old']);
        flash('success', 'Login berhasil.');
        redirect('dashboard');
    }

    public function logout(): void
    {
        if (!verify_csrf()) {
            flash('error', 'Token CSRF tidak valid.');
            redirect('dashboard');
        }

        Auth::logout();
        flash('success', 'Logout berhasil.');
        redirect('login');
    }
}