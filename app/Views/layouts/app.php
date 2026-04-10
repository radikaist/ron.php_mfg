<?php
$appName = env('APP_NAME', 'RON MFG');
$user = auth();
$menus = sidebar_menu();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? $appName) ?></title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            color: #212529;
        }
        .topbar {
            height: 60px;
            background: #343a40;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }
        .brand {
            font-size: 28px;
            font-weight: 700;
        }
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .topbar-user {
            font-size: 14px;
            opacity: .95;
        }
        .logout-form {
            margin: 0;
        }
        .logout-form button {
            background: #dc3545;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 10px 14px;
            cursor: pointer;
            font-size: 14px;
        }
        .wrapper {
            display: flex;
            min-height: calc(100vh - 60px);
        }
        .sidebar {
            width: 260px;
            background: #ffffff;
            border-right: 1px solid #dee2e6;
            padding: 20px 0;
        }
        .sidebar-title {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            color: #6c757d;
            padding: 0 20px 10px;
        }
        .menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .menu li {
            margin: 0;
        }
        .menu a {
            display: block;
            padding: 12px 20px;
            text-decoration: none;
            color: #212529;
            border-left: 4px solid transparent;
        }
        .menu a:hover {
            background: #f8f9fa;
            border-left-color: #0d6efd;
        }
        .content {
            flex: 1;
            padding: 20px;
        }
        .alert {
            padding: 12px 14px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .alert-success {
            background: #d1e7dd;
            color: #0f5132;
        }
        .alert-danger {
            background: #f8d7da;
            color: #842029;
        }
        .card {
            background: #fff;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 4px 20px rgba(0,0,0,.05);
        }
        .role-badge, .permission-badge {
            display: inline-block;
            padding: 6px 10px;
            margin: 4px 6px 0 0;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
        }
        .role-badge {
            background: #cff4fc;
            color: #055160;
        }
        .permission-badge {
            background: #e2e3e5;
            color: #41464b;
        }
    </style>
</head>
<body>
    <div class="topbar">
        <div class="brand"><?= e($appName) ?></div>

        <div class="topbar-right">
            <?php if ($user): ?>
                <div class="topbar-user">
                    Login sebagai <strong><?= e($user['name'] ?? '-') ?></strong>
                </div>
                <form class="logout-form" action="<?= e(base_url('logout')) ?>" method="POST">
                    <?= csrf_field() ?>
                    <button type="submit">Logout</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <div class="wrapper">
        <aside class="sidebar">
            <div class="sidebar-title">Navigation</div>
            <ul class="menu">
                <?php foreach ($menus as $menu): ?>
                    <li>
                        <a href="<?= e($menu['url']) ?>"><?= e($menu['title']) ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </aside>

        <main class="content">
            <?php if ($success = flash_get('success')): ?>
                <div class="alert alert-success"><?= e($success) ?></div>
            <?php endif; ?>

            <?php if ($error = flash_get('error')): ?>
                <div class="alert alert-danger"><?= e($error) ?></div>
            <?php endif; ?>

            <?= $content ?>
        </main>
    </div>
</body>
</html>