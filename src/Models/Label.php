<?php
namespace App\Models;

use App\Config\Database;
use PDO;

class Label {
    public static function all() {
        $db = Database::getConnection();
        $sql = "SELECT gravadora_id AS id, nome FROM tb_gravadoras ORDER BY nome ASC";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}