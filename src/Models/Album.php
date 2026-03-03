<?php
namespace App\Models;

use App\Config\Database;
use PDO;

class Album {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Recupera álbuns paginados ordenados pela data de lançamento (mais recentes primeiro).
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getAllPaginated($limit, $offset) {
        $sql = "SELECT 
                    a.titulo, 
                    a.capa_url, 
                    a.data_lancamento,
                    art.nome AS artista_nome
                FROM tb_albuns a
                INNER JOIN tb_artistas art ON a.artista_id = art.id
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

    /**
     * Retorna a contagem total de álbuns ativos e visíveis.
     * @return int
     */
    public function getTotalCount() {
        $sql = "SELECT COUNT(*) FROM tb_albuns WHERE situacao NOT IN (4, 5) AND deletado = 0";
        return $this->db->query($sql)->fetchColumn();
    }
}