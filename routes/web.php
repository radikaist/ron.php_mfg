<?php

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\HomeController;
use App\Controllers\PermissionController;
use App\Controllers\RoleController;
use App\Controllers\UserController;

require_once APP_PATH . '/Controllers/HomeController.php';
require_once APP_PATH . '/Controllers/AuthController.php';
require_once APP_PATH . '/Controllers/DashboardController.php';
require_once APP_PATH . '/Controllers/UserController.php';
require_once APP_PATH . '/Controllers/RoleController.php';
require_once APP_PATH . '/Controllers/PermissionController.php';

return [
    ['GET', '/', [HomeController::class, 'index']],
    ['GET', '/login', [AuthController::class, 'index']],
    ['POST', '/login', [AuthController::class, 'login']],
    ['POST', '/logout', [AuthController::class, 'logout']],

    ['GET', '/dashboard', [DashboardController::class, 'index']],

    ['GET', '/users', [UserController::class, 'index']],
    ['GET', '/users/create', [UserController::class, 'create']],
    ['POST', '/users/store', [UserController::class, 'store']],

    ['GET', '/roles', [RoleController::class, 'index']],
    ['GET', '/permissions', [PermissionController::class, 'index']],
];