<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    private function __construct() {}
    private function __clone() {}

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            try {
                self::$instance = new PDO(
                    self::dsn(),
                    self::user(),
                    self::password(),
                    self::options()
                );
            } catch (PDOException $e) {
                die('Erro ao conectar no banco');
            }
        }

        return self::$instance;
    }

    private static function dsn(): string
    {
        return sprintf(
            'mysql:host=%s;dbname=%s;charset=utf8mb4',
            getenv('DB_HOST'),
            getenv('DB_NAME')
        );
    }

    private static function user(): string
    {
        return getenv('DB_USER');
    }

    private static function password(): string
    {
        return getenv('DB_PASS');
    }

    private static function options(): array
    {
        return [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];
    }
}
