<?php
namespace App\Models;

use App\Config\Database;
use PDO;

class Situation {
    /**
     * @param bool $incluirInternos Se true, traz inclusive Adquiridos e Descartados
     */
    public static function all($incluirInternos = false) {
        $db = Database::getConnection();
        
        $sql = "SELECT situacao_id, descricao FROM tb_situacoes";
        
        // Se NÃO for para incluir internos, mantém a trava original
        if (!$incluirInternos) {
            $sql .= " WHERE situacao_id NOT IN (4, 5)";
        }
        
        $sql .= " ORDER BY descricao ASC";
        
        return $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}