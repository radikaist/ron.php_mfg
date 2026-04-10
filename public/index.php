<?php

declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
define('CORE_PATH', BASE_PATH . '/core');
define('CONFIG_PATH', BASE_PATH . '/config');
define('ROUTES_PATH', BASE_PATH . '/routes');
define('STORAGE_PATH', BASE_PATH . '/storage');

require_once CORE_PATH . '/helpers.php';
require_once CORE_PATH . '/Env.php';

\Core\Env::load(BASE_PATH . '/.env');

session_name((string) env('SESSION_NAME', 'RONMFGSESSID'));
session_start();

require_once APP_PATH . '/Helpers/menu.php';
require_once CORE_PATH . '/Database.php';
require_once CORE_PATH . '/Model.php';
require_once CORE_PATH . '/Controller.php';
require_once CORE_PATH . '/View.php';
require_once CORE_PATH . '/Auth.php';
require_once CORE_PATH . '/App.php';

$app = new \Core\App();
$app->run();