<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Controller;

class HomeController extends Controller
{
    public function index(): void
    {
        redirect('login');
    }
}