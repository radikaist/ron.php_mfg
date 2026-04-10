<?php $appName = env('APP_NAME', 'RON MFG'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? $appName) ?></title>
    <style>
        :root {
            --primary: #3b82f6;
            --primary-dark: #2563eb;
            --secondary: #06b6d4;
            --accent: #22c55e;
            --bg-1: #eef6ff;
            --bg-2: #f8fbff;
            --card: rgba(255,255,255,.88);
            --border: rgba(255,255,255,.55);
            --text: #1f2937;
            --muted: #6b7280;
            --danger-bg: #fee2e2;
            --danger-text: #b91c1c;
            --success-bg: #dcfce7;
            --success-text: #166534;
            --shadow: 0 20px 40px rgba(59,130,246,.10);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Segoe UI", Tahoma, Arial, sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at top left, rgba(59,130,246,.15), transparent 28%),
                radial-gradient(circle at bottom right, rgba(34,197,94,.10), transparent 24%),
                linear-gradient(135deg, var(--bg-1), var(--bg-2));
            min-height: 100vh;
        }

        .login-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 18px;
        }

        .login-shell {
            width: 100%;
            max-width: 1080px;
            display: grid;
            grid-template-columns: 1.08fr .92fr;
            gap: 28px;
            align-items: center;
        }

        .hero {
            padding: 18px 8px;
        }

        .hero-badge {
            display: inline-block;
            padding: 10px 16px;
            border-radius: 999px;
            background: rgba(255,255,255,.72);
            border: 1px solid rgba(255,255,255,.7);
            color: var(--primary-dark);
            font-weight: 700;
            font-size: 13px;
            box-shadow: 0 10px 24px rgba(0,0,0,.04);
        }

        .hero-title {
            margin: 18px 0 10px;
            font-size: 48px;
            line-height: 1.12;
            font-weight: 800;
            color: #0f172a;
        }

        .hero-title .grad {
            background: linear-gradient(90deg, #2563eb, #06b6d4, #22c55e);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            color: transparent;
        }

        .hero-desc {
            max-width: 580px;
            font-size: 17px;
            line-height: 1.7;
            color: var(--muted);
            margin-bottom: 22px;
        }

        .hero-points {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
            max-width: 620px;
        }

        .hero-point {
            background: rgba(255,255,255,.58);
            border: 1px solid rgba(255,255,255,.65);
            border-radius: 18px;
            padding: 16px 18px;
            box-shadow: 0 12px 24px rgba(59,130,246,.06);
        }

        .hero-point strong {
            display: block;
            margin-bottom: 6px;
            color: #0f172a;
        }

        .hero-point span {
            font-size: 14px;
            color: var(--muted);
            line-height: 1.55;
        }

        .login-box {
            width: 100%;
        }

        .login-logo {
            text-align: center;
            margin-bottom: 18px;
            font-size: 38px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: .5px;
        }

        .login-logo span {
            color: var(--primary);
        }

        .login-card {
            background: var(--card);
            backdrop-filter: blur(14px);
            border: 1px solid var(--border);
            border-radius: 24px;
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .login-card-header {
            padding: 28px 28px 8px;
            text-align: center;
        }

        .login-card-header h1 {
            margin: 0;
            font-size: 24px;
            color: #0f172a;
        }

        .login-card-header p {
            margin: 8px 0 0;
            color: var(--muted);
            font-size: 14px;
        }

        .login-card-body {
            padding: 28px;
        }

        .alert {
            padding: 13px 15px;
            border-radius: 14px;
            margin-bottom: 16px;
            font-size: 14px;
            font-weight: 600;
        }

        .alert-danger {
            background: var(--danger-bg);
            color: var(--danger-text);
        }

        .alert-success {
            background: var(--success-bg);
            color: var(--success-text);
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 700;
            color: #334155;
        }

        .form-control {
            width: 100%;
            height: 50px;
            padding: 12px 16px;
            border: 1px solid #dbeafe;
            background: rgba(255,255,255,.95);
            border-radius: 14px;
            outline: none;
            font-size: 14px;
            color: #0f172a;
            transition: .2s ease;
        }

        .form-control:focus {
            border-color: #93c5fd;
            box-shadow: 0 0 0 5px rgba(59,130,246,.12);
        }

        .btn {
            display: inline-block;
            width: 100%;
            height: 52px;
            border: none;
            border-radius: 14px;
            background: linear-gradient(90deg, #3b82f6, #06b6d4);
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: .2s ease;
            box-shadow: 0 14px 24px rgba(59,130,246,.22);
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 18px 28px rgba(59,130,246,.26);
        }

        .login-demo {
            margin-top: 18px;
            padding: 14px 16px;
            background: rgba(255,255,255,.78);
            border-radius: 16px;
            font-size: 13px;
            color: #475569;
            border: 1px dashed #bfdbfe;
        }

        .login-demo strong {
            color: #0f172a;
        }

        .login-footer {
            text-align: center;
            margin-top: 16px;
            color: #64748b;
            font-size: 13px;
        }

        @media (max-width: 960px) {
            .login-shell {
                grid-template-columns: 1fr;
            }

            .hero {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="login-page">
        <div class="login-shell">
            <section class="hero">
                <div class="hero-badge">Native PHP MVC • Dynamic RBAC • Manufacturing Ready</div>
                <h1 class="hero-title">
                    Framework manufaktur yang
                    <span class="grad">cerah, segar, dan produktif</span>
                </h1>
                <div class="hero-desc">
                    Bangun sistem manufaktur modern berbasis PHP Native, MySQL, MVC, dan Dynamic RBAC
                    dengan antarmuka yang nyaman dipandang dan siap dikembangkan untuk operasional harian.
                </div>

                <div class="hero-points">
                    <div class="hero-point">
                        <strong>Modular MVC</strong>
                        <span>Struktur aplikasi lebih rapi, mudah dikembangkan, dan aman untuk scale-up.</span>
                    </div>
                    <div class="hero-point">
                        <strong>Dynamic RBAC</strong>
                        <span>Role dan permission dapat dikelola fleksibel sesuai kebutuhan perusahaan.</span>
                    </div>
                    <div class="hero-point">
                        <strong>Manufacturing Focus</strong>
                        <span>Siap dikembangkan ke inventory, production order, QC, purchasing, dan reporting.</span>
                    </div>
                    <div class="hero-point">
                        <strong>Fresh UI Experience</strong>
                        <span>Warna cerah, lembut, dan profesional untuk kenyamanan penggunaan jangka panjang.</span>
                    </div>
                </div>
            </section>

            <div class="login-box">
                <div class="login-logo">
                    RON <span>MFG</span>
                </div>

                <div class="login-card">
                    <div class="login-card-header">
                        <h1><?= e(env('APP_NAME', 'RON MFG')) ?></h1>
                        <p>Manufacturing Management System</p>
                    </div>

                    <div class="login-card-body">
                        <?php if ($error = flash_get('error')): ?>
                            <div class="alert alert-danger"><?= e($error) ?></div>
                        <?php endif; ?>

                        <?php if ($success = flash_get('success')): ?>
                            <div class="alert alert-success"><?= e($success) ?></div>
                        <?php endif; ?>

                        <?= $content ?>

                        <div class="login-demo">
                            <strong>Demo login:</strong><br>
                            Username: <strong>admin</strong><br>
                            Password: <strong>password</strong>
                        </div>
                    </div>
                </div>

                <div class="login-footer">
                    &copy; <?= date('Y') ?> <?= e(env('APP_NAME', 'RON MFG')) ?> — Bright Manufacturing Framework
                </div>
            </div>
        </div>
    </div>
</body>
</html>