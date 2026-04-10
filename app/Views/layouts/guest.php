<?php $appName = env('APP_NAME', 'RON MFG'); ?>
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
            font-family: "Segoe UI", Tahoma, Arial, sans-serif;
            background: #e9ecef;
            color: #212529;
        }

        .login-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .login-box {
            width: 100%;
            max-width: 420px;
        }

        .login-logo {
            text-align: center;
            margin-bottom: 18px;
            font-size: 34px;
            font-weight: 700;
            color: #2f3c48;
        }

        .login-logo span {
            color: #0d6efd;
        }

        .login-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,.08);
            overflow: hidden;
            border-top: 4px solid #0d6efd;
        }

        .login-card-header {
            padding: 22px 24px 10px;
            text-align: center;
        }

        .login-card-header h1 {
            margin: 0;
            font-size: 20px;
        }

        .login-card-header p {
            margin: 8px 0 0;
            color: #6c757d;
            font-size: 14px;
        }

        .login-card-body {
            padding: 24px;
        }

        .alert {
            padding: 12px 14px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 14px;
        }

        .alert-danger {
            background: #f8d7da;
            color: #842029;
        }

        .alert-success {
            background: #d1e7dd;
            color: #0f5132;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 600;
            color: #495057;
        }

        .form-control {
            width: 100%;
            height: 46px;
            padding: 10px 14px;
            border: 1px solid #ced4da;
            border-radius: 8px;
            outline: none;
            font-size: 14px;
            transition: .2s ease;
        }

        .form-control:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 4px rgba(13,110,253,.15);
        }

        .btn {
            display: inline-block;
            width: 100%;
            height: 46px;
            border: none;
            border-radius: 8px;
            background: #0d6efd;
            color: #fff;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: .2s ease;
        }

        .btn:hover {
            background: #0b5ed7;
        }

        .login-footer {
            text-align: center;
            margin-top: 16px;
            color: #6c757d;
            font-size: 13px;
        }

        .login-demo {
            margin-top: 14px;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 8px;
            font-size: 13px;
            color: #495057;
            border: 1px dashed #dee2e6;
        }

        .login-demo strong {
            color: #212529;
        }
    </style>
</head>
<body>
    <div class="login-page">
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
                &copy; <?= date('Y') ?> <?= e(env('APP_NAME', 'RON MFG')) ?>
            </div>
        </div>
    </div>
</body>
</html>