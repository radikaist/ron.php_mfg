<?php

declare(strict_types=1);

namespace Core;

use App\Models\User;

require_once APP_PATH . '/Models/User.php';

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

        $userModel = new User();
        $fullUser = $userModel->findWithRolesAndPermissionsById((int) $user['id']);

        if (!$fullUser) {
            return;
        }

        $_SESSION['auth'] = [
            'id' => $fullUser['id'],
            'name' => $fullUser['name'],
            'username' => $fullUser['username'],
            'email' => $fullUser['email'] ?? null,
            'roles' => $fullUser['roles'] ?? [],
            'permissions' => $fullUser['permissions'] ?? [],
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

    public static function hasRole(string $roleCode): bool
    {
        $user = self::user();

        if (!$user) {
            return false;
        }

        return in_array($roleCode, $user['roles'] ?? [], true);
    }

    public static function can(string $permissionCode): bool
    {
        $user = self::user();

        if (!$user) {
            return false;
        }

        return in_array($permissionCode, $user['permissions'] ?? [], true);
    }
}