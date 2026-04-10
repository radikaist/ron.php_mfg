<?php $appName = env('APP_NAME', 'RON MFG'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? $appName) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
        }
        .login-wrapper {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-box {
            width: 380px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,.08);
            padding: 24px;
        }
        .alert {
            padding: 10px 14px;
            border-radius: 6px;
            margin-bottom: 15px;
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
            margin-bottom: 14px;
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
        }
        input[type=text], input[type=password] {
            width: 100%;
            padding: 10px 12px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 12px;
            border: 0;
            background: #0d6efd;
            color: #fff;
            border-radius: 6px;
            cursor: pointer;
        }
        h2, p {
            text-align: center;
        }
    </style>
</head>
<body>
<div class="login-wrapper">
    <div class="login-box">
        <h2><?= e(env('APP_NAME', 'RON MFG')) ?></h2>
        <p>Manufacturing Management System</p>

        <?php if ($error = flash_get('error')): ?>
            <div class="alert alert-danger"><?= e($error) ?></div>
        <?php endif; ?>

        <?php if ($success = flash_get('success')): ?>
            <div class="alert alert-success"><?= e($success) ?></div>
        <?php endif; ?>

        <?= $content ?>
    </div>
</div>
</body>
</html>