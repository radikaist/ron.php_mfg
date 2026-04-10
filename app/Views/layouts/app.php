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
            --sidebar-width: 280px;
            --topbar-height: 72px;
            --footer-height: 58px;

            --primary: #3b82f6;
            --primary-2: #06b6d4;
            --success: #22c55e;
            --warning: #f59e0b;
            --pink: #ec4899;
            --violet: #8b5cf6;

            --sidebar-bg-1: #eff6ff;
            --sidebar-bg-2: #ecfeff;
            --sidebar-border: #dbeafe;

            --body-bg: linear-gradient(135deg, #f6fbff 0%, #eef8ff 45%, #f7fffb 100%);
            --card-bg: rgba(255,255,255,.88);
            --card-border: rgba(255,255,255,.7);
            --card-shadow: 0 20px 45px rgba(59,130,246,.10);

            --text: #0f172a;
            --muted: #64748b;
            --line: #e5eef9;

            --success-bg: #dcfce7;
            --success-text: #166534;
            --danger-bg: #fee2e2;
            --danger-text: #b91c1c;
        }

        * {
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            padding: 0;
            font-family: "Segoe UI", Tahoma, Arial, sans-serif;
            color: var(--text);
            background: var(--body-bg);
        }

        body {
            min-height: 100vh;
        }

        a {
            text-decoration: none;
        }

        .layout {
            min-height: 100vh;
        }

        .topbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--topbar-height);
            background: rgba(255,255,255,.82);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(219,234,254,.8);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            z-index: 1001;
            box-shadow: 0 10px 26px rgba(59,130,246,.05);
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .sidebar-toggle {
            border: none;
            background: linear-gradient(90deg, #3b82f6, #06b6d4);
            color: #fff;
            width: 42px;
            height: 42px;
            border-radius: 12px;
            cursor: pointer;
            font-size: 20px;
            line-height: 1;
            font-weight: 700;
            box-shadow: 0 12px 20px rgba(59,130,246,.18);
        }

        .brand {
            font-size: 28px;
            font-weight: 800;
            color: #1e293b;
            letter-spacing: .2px;
        }

        .brand span {
            background: linear-gradient(90deg, #2563eb, #06b6d4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            color: transparent;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .topbar-user {
            font-size: 14px;
            color: #334155;
            padding: 10px 14px;
            border-radius: 999px;
            background: rgba(255,255,255,.7);
            border: 1px solid #dbeafe;
        }

        .logout-form {
            margin: 0;
        }

        .logout-btn {
            border: none;
            background: linear-gradient(90deg, #ef4444, #f97316);
            color: #fff;
            padding: 11px 16px;
            border-radius: 14px;
            cursor: pointer;
            font-weight: 700;
            box-shadow: 0 12px 20px rgba(239,68,68,.18);
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, .35);
            z-index: 999;
        }

        .sidebar {
            position: fixed;
            top: var(--topbar-height);
            left: 0;
            bottom: 0;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--sidebar-bg-1), var(--sidebar-bg-2));
            border-right: 1px solid var(--sidebar-border);
            padding: 20px 16px;
            overflow-y: auto;
            z-index: 1000;
            transition: transform .28s ease, width .28s ease;
        }

        .sidebar-card {
            background: rgba(255,255,255,.76);
            border: 1px solid rgba(255,255,255,.8);
            border-radius: 24px;
            box-shadow: 0 18px 36px rgba(59,130,246,.07);
            padding: 18px;
            margin-bottom: 18px;
        }

        .sidebar-user-name {
            font-weight: 800;
            font-size: 18px;
            color: #0f172a;
            margin-bottom: 6px;
        }

        .sidebar-user-role {
            font-size: 13px;
            color: #475569;
            line-height: 1.6;
        }

        .sidebar-section {
            padding: 2px 6px 10px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .8px;
            font-weight: 800;
            color: #64748b;
        }

        .sidebar-menu {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .sidebar-menu li {
            margin-bottom: 8px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 14px 16px;
            color: #334155;
            border-radius: 16px;
            transition: .2s ease;
            font-size: 14px;
            font-weight: 600;
            background: rgba(255,255,255,.55);
            border: 1px solid transparent;
        }

        .sidebar-menu a:hover {
            background: rgba(255,255,255,.88);
            border-color: #dbeafe;
            transform: translateX(2px);
        }

        .sidebar-menu a.active {
            background: linear-gradient(90deg, #3b82f6, #06b6d4);
            color: #fff;
            box-shadow: 0 14px 24px rgba(59,130,246,.18);
        }

        .main {
            margin-left: var(--sidebar-width);
            padding-top: var(--topbar-height);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left .28s ease;
        }

        .main-inner {
            flex: 1;
        }

        .content-header {
            padding: 28px 28px 14px;
        }

        .content-title {
            margin: 0;
            font-size: 40px;
            line-height: 1.12;
            font-weight: 800;
            color: #0f172a;
        }

        .content-subtitle {
            margin-top: 10px;
            color: var(--muted);
            font-size: 15px;
        }

        .content-body {
            padding: 0 28px 28px;
        }

        .alert {
            padding: 14px 16px;
            border-radius: 16px;
            margin-bottom: 18px;
            font-size: 14px;
            font-weight: 600;
            border: 1px solid rgba(255,255,255,.7);
            box-shadow: 0 10px 24px rgba(0,0,0,.04);
        }

        .alert-success {
            background: var(--success-bg);
            color: var(--success-text);
        }

        .alert-danger {
            background: var(--danger-bg);
            color: var(--danger-text);
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 22px;
            margin-bottom: 22px;
        }

        .col-3 { grid-column: span 3; }
        .col-4 { grid-column: span 4; }
        .col-5 { grid-column: span 5; }
        .col-6 { grid-column: span 6; }
        .col-7 { grid-column: span 7; }
        .col-8 { grid-column: span 8; }
        .col-12 { grid-column: span 12; }

        .small-box {
            position: relative;
            overflow: hidden;
            border-radius: 24px;
            padding: 22px 22px 20px;
            color: #fff;
            box-shadow: var(--card-shadow);
            min-height: 148px;
        }

        .small-box::after {
            content: "";
            position: absolute;
            right: -22px;
            top: -22px;
            width: 120px;
            height: 120px;
            background: rgba(255,255,255,.14);
            border-radius: 50%;
        }

        .small-box .label {
            position: relative;
            z-index: 1;
            font-size: 14px;
            opacity: .95;
            font-weight: 600;
        }

        .small-box .value {
            position: relative;
            z-index: 1;
            font-size: 42px;
            font-weight: 800;
            margin-top: 12px;
            line-height: 1;
        }

        .small-box .desc {
            position: relative;
            z-index: 1;
            margin-top: 12px;
            font-size: 13px;
            line-height: 1.5;
            opacity: .96;
        }

        .bg-sky {
            background: linear-gradient(135deg, #38bdf8, #2563eb);
        }

        .bg-green {
            background: linear-gradient(135deg, #4ade80, #16a34a);
        }

        .bg-orange {
            background: linear-gradient(135deg, #fbbf24, #f97316);
        }

        .bg-pink {
            background: linear-gradient(135deg, #f472b6, #ec4899);
        }

        .card {
            background: var(--card-bg);
            backdrop-filter: blur(12px);
            border-radius: 24px;
            border: 1px solid var(--card-border);
            box-shadow: var(--card-shadow);
            overflow: hidden;
        }

        .card-header {
            padding: 18px 22px;
            border-bottom: 1px solid var(--line);
            font-weight: 800;
            font-size: 18px;
            color: #0f172a;
            background: rgba(255,255,255,.4);
        }

        .card-body {
            padding: 22px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            border-bottom: 1px solid var(--line);
            padding: 14px 12px;
            text-align: left;
            vertical-align: top;
            font-size: 14px;
        }

        .table th {
            background: rgba(239,246,255,.72);
            color: #0f172a;
            font-weight: 800;
        }

        .info-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 9px 14px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: .2px;
        }

        .badge-sky {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .badge-green {
            background: #dcfce7;
            color: #15803d;
        }

        .badge-orange {
            background: #ffedd5;
            color: #c2410c;
        }

        .badge-pink {
            background: #fce7f3;
            color: #be185d;
        }

        .muted {
            color: var(--muted);
        }

        .welcome-panel {
            background: linear-gradient(135deg, rgba(59,130,246,.10), rgba(6,182,212,.08), rgba(34,197,94,.07));
            border: 1px solid rgba(255,255,255,.8);
            border-radius: 24px;
            box-shadow: var(--card-shadow);
            padding: 24px;
            margin-bottom: 22px;
        }

        .welcome-title {
            font-size: 24px;
            font-weight: 800;
            margin-bottom: 8px;
            color: #0f172a;
        }

        .welcome-desc {
            color: #475569;
            line-height: 1.7;
            font-size: 15px;
            max-width: 900px;
        }

        .footer {
            margin-top: auto;
            min-height: var(--footer-height);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 16px 28px;
            border-top: 1px solid rgba(219,234,254,.8);
            background: rgba(255,255,255,.55);
            backdrop-filter: blur(10px);
            color: #475569;
            font-size: 13px;
        }

        .footer strong {
            color: #0f172a;
        }

        body.sidebar-collapsed .sidebar {
            transform: translateX(calc(-1 * var(--sidebar-width)));
        }

        body.sidebar-collapsed .main {
            margin-left: 0;
        }

        @media (max-width: 1200px) {
            .col-3, .col-4, .col-5, .col-6, .col-7, .col-8 {
                grid-column: span 12;
            }
        }

        @media (max-width: 900px) {
            .sidebar {
                transform: translateX(calc(-1 * var(--sidebar-width)));
            }

            .main {
                margin-left: 0;
            }

            body.sidebar-open .sidebar {
                transform: translateX(0);
            }

            body.sidebar-open .sidebar-overlay {
                display: block;
            }

            .content-title {
                font-size: 32px;
            }
        }

        @media (max-width: 768px) {
            .topbar {
                padding: 12px 16px;
            }

            .brand {
                font-size: 24px;
            }

            .topbar-user {
                display: none;
            }

            .content-header,
            .content-body,
            .footer {
                padding-left: 16px;
                padding-right: 16px;
            }

            .footer {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <div class="layout">
        <header class="topbar">
            <div class="topbar-left">
                <button type="button" class="sidebar-toggle" id="sidebarToggle">☰</button>
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

        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <aside class="sidebar" id="sidebar">
            <div class="sidebar-card">
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
            <div class="main-inner">
                <div class="content-header">
                    <h1 class="content-title"><?= e($title ?? 'Dashboard') ?></h1>
                    <div class="content-subtitle">Bright Native PHP MVC Framework with Dynamic RBAC for Manufacturing System</div>
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
            </div>

            <footer class="footer">
                <div>
                    &copy; <?= date('Y') ?> <strong><?= e($appName) ?></strong>. All rights reserved.
                </div>
                <div>
                    Bright UI • Native PHP MVC • Dynamic RBAC • Manufacturing Ready
                </div>
            </footer>
        </main>
    </div>

    <script>
        (function () {
            const body = document.body;
            const toggle = document.getElementById('sidebarToggle');
            const overlay = document.getElementById('sidebarOverlay');

            function isMobile() {
                return window.innerWidth <= 900;
            }

            toggle.addEventListener('click', function () {
                if (isMobile()) {
                    body.classList.toggle('sidebar-open');
                } else {
                    body.classList.toggle('sidebar-collapsed');
                }
            });

            overlay.addEventListener('click', function () {
                body.classList.remove('sidebar-open');
            });

            window.addEventListener('resize', function () {
                if (!isMobile()) {
                    body.classList.remove('sidebar-open');
                }
            });
        })();
    </script>
</body>
</html>