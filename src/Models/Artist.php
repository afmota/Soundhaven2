<?php
namespace App\Models;

use App\Config\Database;

class Artist {
    public static function all() {
        $db = Database::getConnection();
        return $db->query("SELECT id, nome FROM tb_artistas ORDER BY nome ASC")->fetchAll();
    }
}