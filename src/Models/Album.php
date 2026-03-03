<?php
namespace App\Models;

use App\Config\Database;
use PDO;

class Album {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getAllPaginated($limit, $offset) {
        $sql = "SELECT 
                    a.id,
                    a.titulo, 
                    a.capa_url, 
                    a.data_lancamento,
                    art.nome AS artista_nome,
                    g.nome AS gravadora_nome,
                    t.descricao AS tipo_desc,
                    s.descricao AS situacao_desc
                FROM tb_albuns a
                INNER JOIN tb_artistas art ON a.artista_id = art.id
                LEFT JOIN tb_gravadoras g ON a.gravadora_id = g.id
                LEFT JOIN tb_tipos t ON a.tipo_id = t.id
                LEFT JOIN tb_situacoes s ON a.situacao = s.id
                WHERE a.situacao NOT IN (4, 5) 
                  AND a.deletado = 0
                ORDER BY a.data_lancamento DESC, a.titulo ASC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function getTotalCount() {
        $sql = "SELECT COUNT(*) FROM tb_albuns WHERE situacao NOT IN (4, 5) AND deletado = 0";
        return $this->db->query($sql)->fetchColumn();
    }
}