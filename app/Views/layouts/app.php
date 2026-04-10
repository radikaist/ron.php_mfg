<?php
$appName = env('APP_NAME', 'RON MFG');
$user = auth();
$menus = sidebar_menu();
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? $appName) ?></title>
    <style>
        :root {
            --sidebar-width: 250px;
            --topbar-height: 58px;
            --primary: #0d6efd;
            --dark: #343a40;
            --sidebar-bg: #343a40;
            --sidebar-hover: #3f474e;
            --sidebar-active: #0d6efd;
            --body-bg: #f4f6f9;
            --white: #ffffff;
            --muted: #6c757d;
            --border: #dee2e6;
            --success-bg: #d1e7dd;
            --success-text: #0f5132;
            --danger-bg: #f8d7da;
            --danger-text: #842029;
            --warning-bg: #fff3cd;
            --warning-text: #664d03;
            --shadow: 0 6px 20px rgba(0,0,0,.06);
        }

        * {
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            padding: 0;
            font-family: "Segoe UI", Tahoma, Arial, sans-serif;
            background: var(--body-bg);
            color: #212529;
        }

        a {
            text-decoration: none;
        }

        .topbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--topbar-height);
            background: var(--white);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 18px;
            z-index: 999;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .brand {
            font-size: 22px;
            font-weight: 700;
            color: #2f3c48;
        }

        .brand span {
            color: var(--primary);
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .topbar-user {
            color: #495057;
            font-size: 14px;
        }

        .logout-form {
            margin: 0;
        }

        .logout-btn {
            border: none;
            background: #dc3545;
            color: #fff;
            padding: 10px 14px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
        }

        .sidebar {
            position: fixed;
            top: var(--topbar-height);
            left: 0;
            bottom: 0;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            color: #c2c7d0;
            overflow-y: auto;
        }

        .sidebar-user {
            padding: 18px 18px 14px;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }

        .sidebar-user-name {
            color: #fff;
            font-weight: 700;
            font-size: 15px;
        }

        .sidebar-user-role {
            margin-top: 6px;
            font-size: 12px;
            color: #adb5bd;
        }

        .sidebar-section {
            padding: 14px 18px 8px;
            font-size: 11px;
            text-transform: uppercase;
            font-weight: 700;
            color: #adb5bd;
            letter-spacing: .6px;
        }

        .sidebar-menu {
            list-style: none;
            margin: 0;
            padding: 0 0 12px;
        }

        .sidebar-menu li {
            margin: 0;
        }

        .sidebar-menu a {
            display: block;
            padding: 12px 18px;
            color: #c2c7d0;
            border-left: 4px solid transparent;
            transition: .2s ease;
            font-size: 14px;
        }

        .sidebar-menu a:hover {
            background: var(--sidebar-hover);
            color: #fff;
        }

        .sidebar-menu a.active {
            background: rgba(13,110,253,.16);
            border-left-color: var(--sidebar-active);
            color: #fff;
            font-weight: 600;
        }

        .main {
            margin-left: var(--sidebar-width);
            padding-top: var(--topbar-height);
            min-height: 100vh;
        }

        .content-header {
            padding: 22px 24px 12px;
        }

        .content-title {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            color: #212529;
        }

        .content-subtitle {
            margin-top: 6px;
            color: var(--muted);
            font-size: 14px;
        }

        .content-body {
            padding: 0 24px 24px;
        }

        .alert {
            padding: 14px 16px;
            border-radius: 10px;
            margin-bottom: 18px;
            font-size: 14px;
        }

        .alert-success {
            background: var(--success-bg);
            color: var(--success-text);
        }

        .alert-danger {
            background: var(--danger-bg);
            color: var(--danger-text);
        }

        .card {
            background: #fff;
            border-radius: 12px;
            box-shadow: var(--shadow);
            border: 1px solid rgba(0,0,0,.04);
            overflow: hidden;
        }

        .card-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            font-weight: 700;
            font-size: 16px;
        }

        .card-body {
            padding: 20px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 18px;
            margin-bottom: 18px;
        }

        .col-4 {
            grid-column: span 4;
        }

        .col-6 {
            grid-column: span 6;
        }

        .col-12 {
            grid-column: span 12;
        }

        .small-box {
            border-radius: 12px;
            padding: 18px;
            color: #fff;
            box-shadow: var(--shadow);
        }

        .small-box .label {
            font-size: 14px;
            opacity: .9;
        }

        .small-box .value {
            font-size: 34px;
            font-weight: 800;
            margin-top: 10px;
        }

        .small-box .desc {
            margin-top: 8px;
            font-size: 13px;
            opacity: .92;
        }

        .bg-info {
            background: #17a2b8;
        }

        .bg-success {
            background: #28a745;
        }

        .bg-warning {
            background: #ffc107;
            color: #212529;
        }

        .badge {
            display: inline-block;
            padding: 7px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            margin: 4px 6px 0 0;
        }

        .badge-info {
            background: #cff4fc;
            color: #055160;
        }

        .badge-secondary {
            background: #e2e3e5;
            color: #41464b;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            border-bottom: 1px solid var(--border);
            padding: 12px 10px;
            text-align: left;
            font-size: 14px;
            vertical-align: top;
        }

        .table th {
            background: #f8f9fa;
            font-weight: 700;
        }

        .muted {
            color: var(--muted);
        }

        @media (max-width: 992px) {
            .col-4,
            .col-6 {
                grid-column: span 12;
            }

            .sidebar {
                width: 220px;
            }

            .main {
                margin-left: 220px;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                position: static;
                width: 100%;
                height: auto;
            }

            .topbar {
                position: static;
            }

            .main {
                margin-left: 0;
                padding-top: 0;
            }

            .layout-mobile {
                display: block;
            }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <div class="topbar-left">
            <div class="brand">RON <span>MFG</span></div>
        </div>

        <div class="topbar-right">
            <?php if ($user): ?>
                <div class="topbar-user">
                    Login sebagai <strong><?= e($user['name'] ?? '-') ?></strong>
                </div>
                <form class="logout-form" action="<?= e(base_url('logout')) ?>" method="POST">
                    <?= csrf_field() ?>
                    <button class="logout-btn" type="submit">Logout</button>
                </form>
            <?php endif; ?>
        </div>
    </header>

    <aside class="sidebar">
        <div class="sidebar-user">
            <div class="sidebar-user-name"><?= e($user['name'] ?? '-') ?></div>
            <div class="sidebar-user-role">
                <?= !empty($user['roles']) ? e(implode(', ', $user['roles'])) : 'No role assigned' ?>
            </div>
        </div>

        <div class="sidebar-section">Navigation</div>
        <ul class="sidebar-menu">
            <?php foreach ($menus as $menu): ?>
                <?php
                $menuPath = parse_url($menu['url'], PHP_URL_PATH) ?: '';
                $isActive = $menuPath === $currentPath;
                ?>
                <li>
                    <a href="<?= e($menu['url']) ?>" class="<?= $isActive ? 'active' : '' ?>">
                        <?= e($menu['title']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </aside>

    <main class="main">
        <div class="content-header">
            <h1 class="content-title"><?= e($title ?? 'Dashboard') ?></h1>
            <div class="content-subtitle">Native PHP MVC Framework with Dynamic RBAC for Manufacturing</div>
        </div>

        <div class="content-body">
            <?php if ($success = flash_get('success')): ?>
                <div class="alert alert-success"><?= e($success) ?></div>
            <?php endif; ?>

            <?php if ($error = flash_get('error')): ?>
                <div class="alert alert-danger"><?= e($error) ?></div>
            <?php endif; ?>

            <?= $content ?>
        </div>
    </main>
</body>
</html>