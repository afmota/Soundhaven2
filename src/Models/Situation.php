<?php
namespace App\Models;

use App\Config\Database;

class Situation {
    public static function all() {
        $db = Database::getConnection();
        // Filtramos situações 4 e 5 conforme regra de negócio global
        return $db->query("SELECT id, descricao FROM tb_situacoes WHERE id NOT IN (4, 5) ORDER BY descricao ASC")->fetchAll();
    }
}