<?php

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\HomeController;

require_once APP_PATH . '/Controllers/HomeController.php';
require_once APP_PATH . '/Controllers/AuthController.php';
require_once APP_PATH . '/Controllers/DashboardController.php';

return [
    ['GET', '/', [HomeController::class, 'index']],
    ['GET', '/login', [AuthController::class, 'index']],
    ['POST', '/login', [AuthController::class, 'login']],
    ['GET', '/dashboard', [DashboardController::class, 'index']],
    ['POST', '/logout', [AuthController::class, 'logout']],
];