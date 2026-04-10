<?php

declare(strict_types=1);

namespace Core;

class App
{
    private array $routes = [];

    public function run(): void
    {
        $this->routes = require ROUTES_PATH . '/web.php';

        $method = request_method();
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

        $scriptName = dirname($_SERVER['SCRIPT_NAME'] ?? '');
        if ($scriptName !== '/' && str_starts_with($uri, $scriptName)) {
            $uri = substr($uri, strlen($scriptName));
        }

        $uri = '/' . trim($uri, '/');
        if ($uri === '//') {
            $uri = '/';
        }

        foreach ($this->routes as $route) {
            [$routeMethod, $routeUri, $action] = $route;

            if ($method === $routeMethod && $uri === $routeUri) {
                $this->dispatch($action);
                return;
            }
        }

        http_response_code(404);
        \Core\View::render('errors/404', [], 'layouts/app');
    }

    private function dispatch(array|callable $action): void
    {
        if (is_callable($action)) {
            call_user_func($action);
            return;
        }

        [$controllerName, $method] = $action;

        $class = 'App\\Controllers\\' . $controllerName;

        if (!class_exists($class)) {
            require_once APP_PATH . '/Controllers/' . $controllerName . '.php';
        }

        $controller = new $class();

        if (!method_exists($controller, $method)) {
            http_response_code(500);
            echo 'Method not found.';
            exit;
        }

        $controller->$method();
    }
}