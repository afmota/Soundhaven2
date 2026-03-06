<?php
namespace App\Models;

use App\Config\Database;
use PDO;

class Artist {
    public static function all() {
        $db = Database::getConnection();
        $sql = "SELECT artista_id AS id, nome FROM tb_artistas ORDER BY nome ASC";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}