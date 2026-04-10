<?php

declare(strict_types=1);

namespace Core;

class Auth
{
    public static function user(): ?array
    {
        return $_SESSION['auth'] ?? null;
    }

    public static function check(): bool
    {
        return isset($_SESSION['auth']);
    }

    public static function login(array $user): void
    {
        session_regenerate_id(true);

        $_SESSION['auth'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'username' => $user['username'],
            'email' => $user['email'] ?? null,
        ];
    }

    public static function logout(): void
    {
        unset($_SESSION['auth']);
        session_regenerate_id(true);
    }

    public static function id(): int|string|null
    {
        return $_SESSION['auth']['id'] ?? null;
    }
}