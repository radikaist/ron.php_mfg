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
        }
        .navbar {
            background: #343a40;
            color: white;
            padding: 14px 20px;
        }
        .container {
            padding: 20px;
        }
        .card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 5px 16px rgba(0,0,0,.05);
        }
        .alert {
            padding: 10px 14px;
            border-radius: 6px;
            margin-bottom: 15px;
        }
        .alert-success {
            background: #d1e7dd;
            color: #0f5132;
        }
        .btn-logout {
            float: right;
        }
        .btn-logout button {
            background: #dc3545;
            color: #fff;
            border: 0;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
        }
        .clearfix::after {
            content: "";
            display: block;
            clear: both;
        }
    </style>
</head>
<body>
    <div class="navbar clearfix">
        <strong><?= e($appName) ?></strong>

        <?php if (\Core\Auth::check()): ?>
            <div class="btn-logout">
                <form action="<?= e(base_url('logout')) ?>" method="POST">
                    <?= csrf_field() ?>
                    <button type="submit">Logout</button>
                </form>
            </div>
        <?php endif; ?>
    </div>

    <div class="container">
        <?php if ($success = flash_get('success')): ?>
            <div class="alert alert-success"><?= e($success) ?></div>
        <?php endif; ?>

        <?= $content ?>
    </div>
</body>
</html>