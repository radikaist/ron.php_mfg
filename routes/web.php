<?php

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\HomeController;
use App\Controllers\PermissionController;
use App\Controllers\RbacHealthController;
use App\Controllers\RoleController;
use App\Controllers\UserController;

require_once APP_PATH . '/Controllers/HomeController.php';
require_once APP_PATH . '/Controllers/AuthController.php';
require_once APP_PATH . '/Controllers/DashboardController.php';
require_once APP_PATH . '/Controllers/UserController.php';
require_once APP_PATH . '/Controllers/RoleController.php';
require_once APP_PATH . '/Controllers/PermissionController.php';
require_once APP_PATH . '/Controllers/RbacHealthController.php';

return [
    ['GET', '/', [HomeController::class, 'index']],
    ['GET', '/login', [AuthController::class, 'index']],
    ['POST', '/login', [AuthController::class, 'login']],
    ['POST', '/logout', [AuthController::class, 'logout']],

    ['GET', '/dashboard', [DashboardController::class, 'index']],

    ['GET', '/users', [UserController::class, 'index']],
    ['GET', '/users/create', [UserController::class, 'create']],
    ['POST', '/users/store', [UserController::class, 'store']],
    ['GET', '/users/edit', [UserController::class, 'edit']],
    ['POST', '/users/update', [UserController::class, 'update']],

    ['GET', '/roles', [RoleController::class, 'index']],
    ['GET', '/roles/create', [RoleController::class, 'create']],
    ['POST', '/roles/store', [RoleController::class, 'store']],
    ['GET', '/roles/edit', [RoleController::class, 'edit']],
    ['POST', '/roles/update', [RoleController::class, 'update']],

    ['GET', '/permissions', [PermissionController::class, 'index']],
    ['GET', '/permissions/create', [PermissionController::class, 'create']],
    ['POST', '/permissions/store', [PermissionController::class, 'store']],
    ['GET', '/permissions/edit', [PermissionController::class, 'edit']],
    ['POST', '/permissions/update', [PermissionController::class, 'update']],

    ['GET', '/rbac/health', [RbacHealthController::class, 'index']],
];