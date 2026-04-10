<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Auth;
use Core\Controller;

class DashboardController extends Controller
{
    public function index(): void
    {
        if (!Auth::check()) {
            flash('error', 'Silakan login terlebih dahulu.');
            redirect('login');
        }

        if (!Auth::can('dashboard.view')) {
            http_response_code(403);
            echo '403 Forbidden';
            exit;
        }

        $this->view('dashboard/index', [
            'title' => 'Dashboard',
            'user' => Auth::user(),
        ]);
    }
}