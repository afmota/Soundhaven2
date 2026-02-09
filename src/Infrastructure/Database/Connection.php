<?php
namespace App\Infrastructure\Database;

use PDO;
use PDOException;

class Connection {
    private static ?PDO $instance = null;

    private function __construct() {}

    public static function getInstance(): PDO {
        if (self::$instance === null) {
            try {
                // Em produção, esses valores vêm do ambiente (.env)
                $host = getenv('DB_HOST') ?: 'localhost';
                $db   = getenv('DB_NAME') ?: 'Soundhaven';
                $user = getenv('DB_USER') ?: 'sh_user';
                $pass = getenv('DB_PASS') ?: 'W3azxc*9';

                $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
                
                self::$instance = new PDO($dsn, $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (PDOException $e) {
                // Logar o erro internamente e não exibir a senha no dump
                error_log("Erro de Conexão Soundhaven: " . $e->getMessage());
                throw new \Exception("Erro interno ao conectar ao banco de dados.");
            }
        }
        return self::$instance;
    }
}