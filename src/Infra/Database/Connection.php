<?php
namespace App\Infra\Database;

use PDO;

class Connection
{
    private static ?PDO $instance = null;

    public static function getInstance(string $dbPath): PDO
    {
        if (self::$instance === null) {
            self::$instance = new PDO("sqlite:{$dbPath}");
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$instance;
    }
}


