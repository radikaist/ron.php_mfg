<?php
$appName = env('APP_NAME', 'RON MFG');
$user = auth();
$menus = sidebar_menu();
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

$pageTitle = $title ?? 'Dashboard';

$breadcrumbItems = [];
$pathSegments = array_values(array_filter(explode('/', trim($currentPath, '/'))));

$breadcrumbItems[] = [
    'label' => 'Home',
    'url' => base_url('dashboard'),
];

if (!empty($pathSegments)) {
    $buildPath = '';
    foreach ($pathSegments as $index => $segment) {
        $buildPath .= '/' . $segment;
        $label = ucwords(str_replace(['-', '_'], ' ', $segment));

        $breadcrumbItems[] = [
            'label' => $label,
            'url' => base_url(ltrim($buildPath, '/')),
            'active' => $index === array_key_last($pathSegments),
        ];
    }
} else {
    $breadcrumbItems[] = [
        'label' => 'Dashboard',
        'url' => base_url('dashboard'),
        'active' => true,
    ];
}

$toastSuccess = flash_get('success');
$toastError = flash_get('error');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?></title>
    <style>
        :root {
            --sidebar-width: 300px;
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
            --topbar-bg: rgba(255,255,255,.82);
            --footer-bg: rgba(255,255,255,.55);
            --menu-item-bg: rgba(255,255,255,.55);
            --menu-item-hover: rgba(255,255,255,.88);
            --sidebar-card-bg: rgba(255,255,255,.76);
            --table-head-bg: rgba(239,246,255,.72);
            --welcome-bg: linear-gradient(135deg, rgba(59,130,246,.10), rgba(6,182,212,.08), rgba(34,197,94,.07));
            --input-bg: rgba(255,255,255,.92);
        }

        body.theme-dark {
            --sidebar-bg-1: #172033;
            --sidebar-bg-2: #0f172a;
            --sidebar-border: rgba(255,255,255,.06);
            --body-bg: linear-gradient(135deg, #0f172a 0%, #111827 45%, #172033 100%);
            --card-bg: rgba(30,41,59,.78);
            --card-border: rgba(255,255,255,.06);
            --card-shadow: 0 20px 45px rgba(0,0,0,.28);
            --text: #e5eefc;
            --muted: #94a3b8;
            --line: rgba(255,255,255,.08);
            --topbar-bg: rgba(15,23,42,.82);
            --footer-bg: rgba(15,23,42,.52);
            --menu-item-bg: rgba(255,255,255,.04);
            --menu-item-hover: rgba(255,255,255,.08);
            --sidebar-card-bg: rgba(255,255,255,.04);
            --table-head-bg: rgba(255,255,255,.05);
            --welcome-bg: linear-gradient(135deg, rgba(59,130,246,.18), rgba(6,182,212,.10), rgba(34,197,94,.10));
            --input-bg: rgba(255,255,255,.04);
        }

        * { box-sizing: border-box; }
        html, body {
            margin: 0;
            padding: 0;
            font-family: "Segoe UI", Tahoma, Arial, sans-serif;
            color: var(--text);
            background: var(--body-bg);
            transition: background .25s ease, color .25s ease;
        }

        body { min-height: 100vh; }
        a { text-decoration: none; color: inherit; }
        .layout { min-height: 100vh; }

        .topbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            height: var(--topbar-height);
            background: var(--topbar-bg);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--line);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            z-index: 1001;
            box-shadow: 0 10px 26px rgba(59,130,246,.05);
        }

        .topbar-left, .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-toggle, .theme-toggle {
            border: none;
            color: #fff;
            min-width: 42px;
            height: 42px;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 700;
            padding: 0 12px;
            box-shadow: 0 12px 20px rgba(59,130,246,.18);
        }

        .sidebar-toggle { background: linear-gradient(90deg, #3b82f6, #06b6d4); font-size: 18px; }
        .theme-toggle { background: linear-gradient(90deg, #8b5cf6, #ec4899); font-size: 14px; }

        .brand { font-size: 28px; font-weight: 800; color: var(--text); }
        .brand span {
            background: linear-gradient(90deg, #2563eb, #06b6d4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            color: transparent;
        }

        .profile-menu { position: relative; }
        .profile-btn {
            font-size: 14px;
            color: var(--text);
            padding: 10px 14px;
            border-radius: 999px;
            background: rgba(255,255,255,.08);
            border: 1px solid var(--line);
            cursor: pointer;
            font-weight: 700;
        }

        .profile-dropdown {
            display: none;
            position: absolute;
            right: 0;
            top: calc(100% + 10px);
            min-width: 240px;
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 18px;
            box-shadow: var(--card-shadow);
            padding: 12px;
            z-index: 1100;
        }

        .profile-menu.open .profile-dropdown { display: block; }
        .profile-name { font-weight: 800; margin-bottom: 4px; color: var(--text); }
        .profile-email { font-size: 12px; color: var(--muted); margin-bottom: 10px; }
        .profile-role { font-size: 12px; color: var(--muted); line-height: 1.6; margin-bottom: 12px; }

        .logout-form { margin: 0; }
        .logout-btn {
            width: 100%;
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
            background: rgba(15,23,42,.35);
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
            transition: transform .28s ease;
        }

        .sidebar-card {
            background: var(--sidebar-card-bg);
            border: 1px solid var(--card-border);
            border-radius: 24px;
            box-shadow: 0 18px 36px rgba(59,130,246,.07);
            padding: 18px;
            margin-bottom: 18px;
        }

        .sidebar-user-name { font-weight: 800; font-size: 18px; color: var(--text); margin-bottom: 6px; }
        .sidebar-user-role { font-size: 13px; color: var(--muted); line-height: 1.6; }

        .sidebar-section {
            padding: 2px 6px 10px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .8px;
            font-weight: 800;
            color: var(--muted);
        }

        .sidebar-menu { list-style: none; margin: 0; padding: 0; }
        .sidebar-menu li { margin-bottom: 10px; }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            color: var(--text);
            border-radius: 18px;
            transition: .2s ease;
            font-size: 14px;
            font-weight: 600;
            background: var(--menu-item-bg);
            border: 1px solid transparent;
        }

        .sidebar-menu a:hover {
            background: var(--menu-item-hover);
            border-color: var(--line);
            transform: translateX(2px);
        }

        .sidebar-menu a.active {
            background: linear-gradient(90deg, #3b82f6, #06b6d4);
            color: #fff;
            box-shadow: 0 14px 24px rgba(59,130,246,.18);
        }

        .menu-icon {
            width: 28px;
            height: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: rgba(255,255,255,.22);
            font-size: 15px;
            flex-shrink: 0;
        }

        .menu-content { display: flex; flex-direction: column; min-width: 0; }
        .menu-title { font-size: 14px; font-weight: 700; line-height: 1.3; }
        .menu-desc { font-size: 11px; opacity: .82; line-height: 1.4; margin-top: 2px; }

        .main {
            margin-left: var(--sidebar-width);
            padding-top: var(--topbar-height);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left .28s ease;
        }

        .main-inner { flex: 1; }
        .content-header { padding: 24px 28px 12px; }

        .breadcrumb {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center;
            margin-bottom: 12px;
            font-size: 13px;
            color: var(--muted);
        }

        .breadcrumb a { color: var(--muted); }
        .breadcrumb-sep { opacity: .5; }
        .breadcrumb-current { color: var(--text); font-weight: 700; }

        .content-title {
            margin: 0;
            font-size: 40px;
            line-height: 1.12;
            font-weight: 800;
            color: var(--text);
        }

        .content-subtitle { margin-top: 10px; color: var(--muted); font-size: 15px; }
        .content-body { padding: 0 28px 28px; }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 22px;
        }

        .quick-action {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            padding: 18px;
            display: flex;
            align-items: center;
            gap: 14px;
            transition: .2s ease;
        }

        .quick-action:hover { transform: translateY(-2px); }

        .quick-action-icon {
            width: 48px;
            height: 48px;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: #fff;
            flex-shrink: 0;
        }

        .qa-blue { background: linear-gradient(135deg, #3b82f6, #06b6d4); }
        .qa-green { background: linear-gradient(135deg, #22c55e, #16a34a); }
        .qa-orange { background: linear-gradient(135deg, #f59e0b, #f97316); }
        .qa-pink { background: linear-gradient(135deg, #ec4899, #8b5cf6); }

        .quick-action-title { font-weight: 800; font-size: 14px; color: var(--text); }
        .quick-action-desc { color: var(--muted); font-size: 12px; margin-top: 4px; line-height: 1.5; }

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
            min-height: 160px;
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

        .small-box .label { position: relative; z-index: 1; font-size: 14px; opacity: .95; font-weight: 700; }
        .small-box .value { position: relative; z-index: 1; font-size: 42px; font-weight: 800; margin-top: 12px; line-height: 1; }
        .small-box .desc { position: relative; z-index: 1; margin-top: 12px; font-size: 13px; line-height: 1.6; opacity: .97; }
        .small-box .mini { position: relative; z-index: 1; margin-top: 8px; display: inline-block; font-size: 11px; font-weight: 700; background: rgba(255,255,255,.16); padding: 7px 10px; border-radius: 999px; }

        .bg-sky { background: linear-gradient(135deg, #38bdf8, #2563eb); }
        .bg-green { background: linear-gradient(135deg, #4ade80, #16a34a); }
        .bg-orange { background: linear-gradient(135deg, #fbbf24, #f97316); }
        .bg-pink { background: linear-gradient(135deg, #f472b6, #8b5cf6); }

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
            color: var(--text);
            background: rgba(255,255,255,.04);
        }

        .card-body { padding: 22px; }

        .toolbar {
            display: flex;
            gap: 12px;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 18px;
        }

        .search-box { width: 100%; max-width: 360px; }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
            font-size: 14px;
            color: var(--text);
        }

        .form-control, .form-select, .form-textarea {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid var(--line);
            border-radius: 14px;
            background: var(--input-bg);
            color: var(--text);
            outline: none;
            transition: .2s ease;
            font-size: 14px;
        }

        .form-control:focus, .form-select:focus, .form-textarea:focus {
            border-color: #93c5fd;
            box-shadow: 0 0 0 4px rgba(59,130,246,.12);
        }

        .form-hint { margin-top: 6px; font-size: 12px; color: var(--muted); }

        .checkbox-label {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            color: var(--text);
        }

        .permission-tools {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
        }

        .permission-tool-btn {
            border: 1px solid var(--line);
            background: transparent;
            color: var(--text);
            padding: 8px 12px;
            border-radius: 12px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 700;
        }

        .permission-counter {
            margin-left: auto;
            font-size: 13px;
            color: var(--muted);
            font-weight: 700;
        }

        .permission-group-wrap {
            border: 1px solid var(--line);
            background: var(--input-bg);
            border-radius: 18px;
            max-height: 320px;
            overflow-y: auto;
            padding: 10px;
        }

        .permission-group {
            border: 1px solid var(--line);
            border-radius: 16px;
            margin-bottom: 12px;
            overflow: hidden;
            background: rgba(255,255,255,.25);
        }

        .permission-group:last-child {
            margin-bottom: 0;
        }

        .permission-group-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 14px;
            cursor: pointer;
            background: rgba(59,130,246,.06);
        }

        .permission-group-title {
            font-size: 14px;
            font-weight: 800;
            color: var(--text);
            text-transform: capitalize;
        }

        .permission-group-meta {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--muted);
            font-size: 12px;
            font-weight: 700;
        }

        .permission-group-body {
            padding: 8px;
        }

        .permission-group.collapsed .permission-group-body {
            display: none;
        }

        .checkbox-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 10px 12px;
            border-radius: 14px;
            transition: .2s ease;
        }

        .checkbox-item:hover {
            background: rgba(59,130,246,.06);
        }

        .checkbox-item input[type="checkbox"] {
            margin-top: 3px;
            transform: scale(1.1);
            cursor: pointer;
        }

        .checkbox-item-text {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .checkbox-item-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--text);
        }

        .checkbox-item-desc {
            font-size: 12px;
            color: var(--muted);
            line-height: 1.5;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: none;
            padding: 12px 18px;
            border-radius: 14px;
            font-weight: 700;
            cursor: pointer;
            color: #fff;
        }

        .btn-primary { background: linear-gradient(90deg,#3b82f6,#06b6d4); }
        .btn-success { background: linear-gradient(90deg,#22c55e,#16a34a); }
        .btn-warning { background: linear-gradient(90deg,#f59e0b,#f97316); }
        .btn-pink { background: linear-gradient(90deg,#ec4899,#8b5cf6); }

        .btn-outline {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 18px;
            border-radius: 14px;
            border: 1px solid var(--line);
            color: var(--text);
            background: transparent;
            font-weight: 700;
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
            color: var(--text);
        }

        .table th {
            background: var(--table-head-bg);
            color: var(--text);
            font-weight: 800;
            cursor: pointer;
            user-select: none;
        }

        .table th.no-sort {
            cursor: default;
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

        .badge-sky { background: #dbeafe; color: #1d4ed8; }
        .badge-green { background: #dcfce7; color: #15803d; }
        .badge-orange { background: #ffedd5; color: #c2410c; }
        .badge-pink { background: #fce7f3; color: #be185d; }

        body.theme-dark .badge-sky { background: rgba(59,130,246,.18); color: #bfdbfe; }
        body.theme-dark .badge-green { background: rgba(34,197,94,.18); color: #bbf7d0; }
        body.theme-dark .badge-orange { background: rgba(245,158,11,.18); color: #fed7aa; }
        body.theme-dark .badge-pink { background: rgba(236,72,153,.18); color: #fbcfe8; }

        .muted { color: var(--muted); }

        .welcome-panel {
            background: var(--welcome-bg);
            border: 1px solid var(--card-border);
            border-radius: 24px;
            box-shadow: var(--card-shadow);
            padding: 24px;
            margin-bottom: 22px;
        }

        .welcome-title { font-size: 24px; font-weight: 800; margin-bottom: 8px; color: var(--text); }
        .welcome-desc { color: var(--muted); line-height: 1.7; font-size: 15px; max-width: 900px; }

        .footer {
            margin-top: auto;
            min-height: var(--footer-height);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 16px 28px;
            border-top: 1px solid var(--line);
            background: var(--footer-bg);
            backdrop-filter: blur(10px);
            color: var(--muted);
            font-size: 13px;
        }

        .footer strong { color: var(--text); }

        .toast-wrap {
            position: fixed;
            top: 90px;
            right: 20px;
            z-index: 1200;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .toast {
            min-width: 280px;
            max-width: 360px;
            padding: 14px 16px;
            border-radius: 16px;
            color: #fff;
            box-shadow: 0 18px 30px rgba(0,0,0,.16);
            animation: slideIn .25s ease;
            font-size: 14px;
            font-weight: 700;
        }

        .toast-success { background: linear-gradient(90deg, #22c55e, #16a34a); }
        .toast-error { background: linear-gradient(90deg, #ef4444, #f97316); }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-8px) translateX(12px); }
            to { opacity: 1; transform: translateY(0) translateX(0); }
        }

        body.sidebar-collapsed .sidebar { transform: translateX(calc(-1 * var(--sidebar-width))); }
        body.sidebar-collapsed .main { margin-left: 0; }

        @media (max-width: 1200px) {
            .quick-actions { grid-template-columns: repeat(2, 1fr); }
            .col-3, .col-4, .col-5, .col-6, .col-7, .col-8 { grid-column: span 12; }
        }

        @media (max-width: 900px) {
            .sidebar { transform: translateX(calc(-1 * var(--sidebar-width))); }
            .main { margin-left: 0; }
            body.sidebar-open .sidebar { transform: translateX(0); }
            body.sidebar-open .sidebar-overlay { display: block; }
            .content-title { font-size: 32px; }
        }

        @media (max-width: 768px) {
            .topbar { padding: 12px 16px; }
            .brand { font-size: 24px; }
            .quick-actions { grid-template-columns: 1fr; }
            .content-header, .content-body, .footer { padding-left: 16px; padding-right: 16px; }
            .footer { flex-direction: column; align-items: flex-start; }
            .toast-wrap { left: 16px; right: 16px; top: 82px; }
            .toast { max-width: unset; min-width: unset; width: 100%; }
            .permission-counter { width: 100%; margin-left: 0; }
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
                <button type="button" class="theme-toggle" id="themeToggle">Theme</button>

                <?php if ($user): ?>
                    <div class="profile-menu" id="profileMenu">
                        <button type="button" class="profile-btn" id="profileBtn">
                            <?= e($user['name'] ?? 'User') ?>
                        </button>

                        <div class="profile-dropdown" id="profileDropdown">
                            <div class="profile-name"><?= e($user['name'] ?? '-') ?></div>
                            <div class="profile-email"><?= e($user['email'] ?? '-') ?></div>
                            <div class="profile-role">
                                <?= !empty($user['roles']) ? e(implode(', ', $user['roles'])) : 'No role assigned' ?>
                            </div>

                            <form class="logout-form" action="<?= e(base_url('logout')) ?>" method="POST" data-confirm="Yakin ingin logout?">
                                <?= csrf_field() ?>
                                <button class="logout-btn" type="submit">Logout</button>
                            </form>
                        </div>
                    </div>
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
                            <span class="menu-icon"><?= e($menu['icon'] ?? '•') ?></span>
                            <span class="menu-content">
                                <span class="menu-title"><?= e($menu['title']) ?></span>
                                <span class="menu-desc"><?= e($menu['description'] ?? '') ?></span>
                            </span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </aside>

        <main class="main">
            <div class="main-inner">
                <div class="content-header">
                    <div class="breadcrumb">
                        <?php foreach ($breadcrumbItems as $index => $item): ?>
                            <?php if ($index > 0): ?>
                                <span class="breadcrumb-sep">/</span>
                            <?php endif; ?>

                            <?php if (!empty($item['active'])): ?>
                                <span class="breadcrumb-current"><?= e($item['label']) ?></span>
                            <?php else: ?>
                                <a href="<?= e($item['url']) ?>"><?= e($item['label']) ?></a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>

                    <h1 class="content-title"><?= e($pageTitle) ?></h1>
                    <div class="content-subtitle">Bright Native PHP MVC Framework with Dynamic RBAC for Manufacturing System</div>
                </div>

                <div class="content-body">
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

    <div class="toast-wrap" id="toastWrap">
        <?php if ($toastSuccess): ?>
            <div class="toast toast-success"><?= e($toastSuccess) ?></div>
        <?php endif; ?>

        <?php if ($toastError): ?>
            <div class="toast toast-error"><?= e($toastError) ?></div>
        <?php endif; ?>
    </div>

    <script>
        (function () {
            const body = document.body;
            const toggle = document.getElementById('sidebarToggle');
            const overlay = document.getElementById('sidebarOverlay');
            const themeToggle = document.getElementById('themeToggle');
            const themeKey = 'ronmfg_theme';
            const profileMenu = document.getElementById('profileMenu');
            const profileBtn = document.getElementById('profileBtn');

            function isMobile() {
                return window.innerWidth <= 900;
            }

            if (toggle) {
                toggle.addEventListener('click', function () {
                    if (isMobile()) {
                        body.classList.toggle('sidebar-open');
                    } else {
                        body.classList.toggle('sidebar-collapsed');
                    }
                });
            }

            if (overlay) {
                overlay.addEventListener('click', function () {
                    body.classList.remove('sidebar-open');
                });
            }

            window.addEventListener('resize', function () {
                if (!isMobile()) {
                    body.classList.remove('sidebar-open');
                }
            });

            function applyTheme(theme) {
                if (theme === 'dark') {
                    body.classList.add('theme-dark');
                } else {
                    body.classList.remove('theme-dark');
                }
            }

            const savedTheme = localStorage.getItem(themeKey) || 'light';
            applyTheme(savedTheme);

            if (themeToggle) {
                themeToggle.addEventListener('click', function () {
                    const currentTheme = body.classList.contains('theme-dark') ? 'dark' : 'light';
                    const nextTheme = currentTheme === 'dark' ? 'light' : 'dark';
                    localStorage.setItem(themeKey, nextTheme);
                    applyTheme(nextTheme);
                });
            }

            if (profileBtn && profileMenu) {
                profileBtn.addEventListener('click', function () {
                    profileMenu.classList.toggle('open');
                });

                document.addEventListener('click', function (e) {
                    if (!profileMenu.contains(e.target)) {
                        profileMenu.classList.remove('open');
                    }
                });
            }

            document.querySelectorAll('[data-confirm]').forEach(function (el) {
                el.addEventListener('submit', function (e) {
                    const message = el.getAttribute('data-confirm') || 'Apakah Anda yakin?';
                    if (!window.confirm(message)) {
                        e.preventDefault();
                    }
                });
            });

            const toasts = document.querySelectorAll('.toast');
            if (toasts.length) {
                setTimeout(function () {
                    toasts.forEach(function (toast) {
                        toast.style.transition = 'opacity .3s ease, transform .3s ease';
                        toast.style.opacity = '0';
                        toast.style.transform = 'translateY(-8px)';
                        setTimeout(function () {
                            toast.remove();
                        }, 300);
                    });
                }, 2800);
            }

            document.querySelectorAll('[data-table-filter]').forEach(function (input) {
                input.addEventListener('input', function () {
                    const keyword = input.value.toLowerCase().trim();
                    const tableId = input.getAttribute('data-table-filter');
                    const table = document.getElementById(tableId);

                    if (!table) return;

                    table.querySelectorAll('tbody tr').forEach(function (row) {
                        if (row.classList.contains('empty-row')) return;
                        const text = row.innerText.toLowerCase();
                        row.style.display = text.includes(keyword) ? '' : 'none';
                    });
                });
            });

            document.querySelectorAll('.sortable-table').forEach(function (table) {
                const headers = table.querySelectorAll('thead th');

                headers.forEach(function (header, index) {
                    if (header.classList.contains('no-sort')) return;

                    let asc = true;

                    header.addEventListener('click', function () {
                        const tbody = table.querySelector('tbody');
                        const rows = Array.from(tbody.querySelectorAll('tr')).filter(function (row) {
                            return !row.classList.contains('empty-row');
                        });

                        rows.sort(function (a, b) {
                            const aText = (a.children[index]?.innerText || '').trim().toLowerCase();
                            const bText = (b.children[index]?.innerText || '').trim().toLowerCase();

                            const aNum = parseFloat(aText);
                            const bNum = parseFloat(bText);

                            if (!isNaN(aNum) && !isNaN(bNum)) {
                                return asc ? aNum - bNum : bNum - aNum;
                            }

                            return asc
                                ? aText.localeCompare(bText)
                                : bText.localeCompare(aText);
                        });

                        rows.forEach(function (row) {
                            tbody.appendChild(row);
                        });

                        asc = !asc;
                    });
                });
            });

            document.querySelectorAll('[data-permission-panel]').forEach(function (panel) {
                const checkboxes = panel.querySelectorAll('input[type="checkbox"][name="permission_ids[]"]');
                const counter = panel.querySelector('[data-permission-counter]');
                const checkAllBtn = panel.querySelector('[data-check-all]');
                const uncheckAllBtn = panel.querySelector('[data-uncheck-all]');

                function updateCounter() {
                    let checked = 0;
                    checkboxes.forEach(function (checkbox) {
                        if (checkbox.checked) checked++;
                    });

                    if (counter) {
                        counter.textContent = checked + ' permission dipilih';
                    }
                }

                if (checkAllBtn) {
                    checkAllBtn.addEventListener('click', function () {
                        checkboxes.forEach(function (checkbox) {
                            checkbox.checked = true;
                        });
                        updateCounter();
                    });
                }

                if (uncheckAllBtn) {
                    uncheckAllBtn.addEventListener('click', function () {
                        checkboxes.forEach(function (checkbox) {
                            checkbox.checked = false;
                        });
                        updateCounter();
                    });
                }

                checkboxes.forEach(function (checkbox) {
                    checkbox.addEventListener('change', updateCounter);
                });

                panel.querySelectorAll('[data-group-toggle]').forEach(function (toggleBtn) {
                    toggleBtn.addEventListener('click', function () {
                        const group = toggleBtn.closest('.permission-group');
                        if (group) {
                            group.classList.toggle('collapsed');
                        }
                    });
                });

                updateCounter();
            });
        })();
    </script>
</body>
</html>