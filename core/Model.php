<?php

declare(strict_types=1);

namespace Core;

use PDO;

class Model
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }
}