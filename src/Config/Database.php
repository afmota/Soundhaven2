<?php
namespace App\Config;

use PDO;
use PDOException;

class Database {
    private static $instance = null;

    public static function getConnection() {
        if (self::$instance === null) {
            $config = parse_ini_file(__DIR__ . '/../../.env');
            try {
                $dsn = "mysql:host={$config['DB_HOST']};dbname={$config['DB_NAME']};charset={$config['DB_CHARSET']}";
                self::$instance = new PDO($dsn, $config['DB_USER'], $config['DB_PASS'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (PDOException $e) {
                die("Erro na conexão técnica: " . $e->getMessage());
            }
        }
        return self::$instance;
    }
}