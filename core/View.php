<?php

declare(strict_types=1);

namespace Core;

class View
{
    public static function render(string $view, array $data = [], string $layout = 'layouts/app'): void
    {
        $viewPath = APP_PATH . '/Views/' . $view . '.php';
        $layoutPath = APP_PATH . '/Views/' . $layout . '.php';

        if (!file_exists($viewPath)) {
            http_response_code(500);
            echo "View {$view} not found.";
            exit;
        }

        extract($data);

        ob_start();
        require $viewPath;
        $content = ob_get_clean();

        if (file_exists($layoutPath)) {
            require $layoutPath;
            return;
        }

        echo $content;
    }
}