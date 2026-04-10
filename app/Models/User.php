<?php

declare(strict_types=1);

namespace App\Models;

use Core\Model;

class User extends Model
{
    public function findByUsername(string $username): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
        $stmt->execute([
            'username' => $username,
        ]);

        return $stmt->fetch();
    }
}