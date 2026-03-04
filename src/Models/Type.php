<?php
namespace App\Models;

use App\Config\Database;

class Type {
    public static function all() {
        $db = Database::getConnection();
        return $db->query("SELECT id, descricao FROM tb_tipos ORDER BY descricao ASC")->fetchAll();
    }
}