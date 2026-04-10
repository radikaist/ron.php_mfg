<?php

declare(strict_types=1);

if (!function_exists('env')) {
    function env(string $key, mixed $default = null): mixed
    {
        return $_ENV[$key] ?? $_SERVER[$key] ?? $default;
    }
}

if (!function_exists('config')) {
    function config(string $file): array
    {
        $path = CONFIG_PATH . '/' . $file . '.php';

        if (!file_exists($path)) {
            return [];
        }

        return require $path;
    }
}

if (!function_exists('base_url')) {
    function base_url(string $path = ''): string
    {
        $base = rtrim((string) env('APP_URL', ''), '/');
        $path = ltrim($path, '/');

        return $path ? $base . '/' . $path : $base;
    }
}

if (!function_exists('redirect')) {
    function redirect(string $path): never
    {
        header('Location: ' . base_url($path));
        exit;
    }
}

if (!function_exists('request_method')) {
    function request_method(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }
}

if (!function_exists('old')) {
    function old(string $key, mixed $default = ''): mixed
    {
        return $_SESSION['_old'][$key] ?? $default;
    }
}

if (!function_exists('old_or')) {
    function old_or(string $key, mixed $fallback = ''): mixed
    {
        return $_SESSION['_old'][$key] ?? $fallback;
    }
}

if (!function_exists('is_checked')) {
    function is_checked(string $key, mixed $value = 1, mixed $default = null): string
    {
        $oldValue = $_SESSION['_old'][$key] ?? $default;

        if (is_array($oldValue)) {
            return in_array((string) $value, array_map('strval', $oldValue), true) ? 'checked' : '';
        }

        return ((string) $oldValue === (string) $value) ? 'checked' : '';
    }
}

if (!function_exists('flash')) {
    function flash(string $key, mixed $value): void
    {
        $_SESSION['_flash'][$key] = $value;
    }
}

if (!function_exists('flash_get')) {
    function flash_get(string $key, mixed $default = null): mixed
    {
        $value = $_SESSION['_flash'][$key] ?? $default;
        unset($_SESSION['_flash'][$key]);
        return $value;
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['_csrf_token'];
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string
    {
        return '<input type="hidden" name="_token" value="' . htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') . '">';
    }
}

if (!function_exists('verify_csrf')) {
    function verify_csrf(): bool
    {
        $token = $_POST['_token'] ?? '';
        $sessionToken = $_SESSION['_csrf_token'] ?? '';

        return hash_equals($sessionToken, $token);
    }
}

if (!function_exists('e')) {
    function e(string|null $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('auth')) {
    function auth(): ?array
    {
        return \Core\Auth::user();
    }
}

if (!function_exists('can')) {
    function can(string $permissionCode): bool
    {
        return \Core\Auth::can($permissionCode);
    }
}

if (!function_exists('has_role')) {
    function has_role(string $roleCode): bool
    {
        return \Core\Auth::hasRole($roleCode);
    }
}