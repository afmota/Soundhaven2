<?php
namespace App\Repositories;

use App\Config\Database;
use PDO;

class ArtistaRepository {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function buscarArtistasComAlbuns($limit = 24, $offset = 0) {
        // O DISTINCT garante que o artista não apareça repetido se tiver 10 álbuns
        // O INNER JOIN com tb_albuns e tb_midias garante que só venham artistas "com dono" na coleção
        $sql = "SELECT DISTINCT 
                    art.*, 
                    p.nome AS pais_nome, 
                    p.codigo_iso AS codigo_iso,
                    g.descricao AS genero_nome
                FROM tb_artistas art
                INNER JOIN tb_albuns alb ON art.artista_id = alb.artista_id
                INNER JOIN tb_midias mid ON alb.album_id = mid.album_id
                LEFT JOIN tb_paises p ON art.pais_origem = p.pais_id
                LEFT JOIN tb_generos g ON art.genero_principal = g.genero_id
                WHERE mid.ativo = 1
                ORDER BY art.nome ASC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contarTotalArtistasComAlbuns() {
        $sql = "SELECT COUNT(DISTINCT art.artista_id) 
                FROM tb_artistas art
                INNER JOIN tb_albuns alb ON art.artista_id = alb.artista_id
                INNER JOIN tb_midias mid ON alb.album_id = mid.album_id
                WHERE mid.ativo = 1";

        return $this->db->query($sql)->fetchColumn();
    }
}