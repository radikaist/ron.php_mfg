<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Dashboard;
use Core\Auth;
use Core\Controller;

require_once APP_PATH . '/Models/Dashboard.php';

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
            $this->view('errors/403', [
                'title' => '403 Forbidden',
            ]);
            return;
        }

        $dashboardModel = new Dashboard();
        $stats = $dashboardModel->counts();

        $this->view('dashboard/index', [
            'title' => 'Dashboard',
            'user' => Auth::user(),
            'stats' => $stats,
        ]);
    }
}