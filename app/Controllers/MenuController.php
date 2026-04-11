<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Menu;
use Core\Auth;
use Core\Controller;

require_once APP_PATH . '/Models/Menu.php';

class MenuController extends Controller
{
    public function index(): void
    {
        if (!Auth::check()) {
            flash('error', 'Silakan login terlebih dahulu.');
            redirect('login');
        }

        if (!Auth::can('menus.view')) {
            http_response_code(403);
            $this->view('errors/403', ['title' => '403 Forbidden']);
            return;
        }

        $menuModel = new Menu();

        $this->view('menus/index', [
            'title' => 'Menu Management',
            'menus' => $menuModel->all(),
        ]);
    }
}