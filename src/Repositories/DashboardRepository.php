<?php
namespace App\Repositories;

class DashboardRepository {
    private $db;

    public function __construct(\PDO $pdo) {
        $this->db = $pdo;
    }

    public function buscarDadosGerais() {
        // Centralizamos as queries de contagem aqui
        $sql = "SELECT 
                (SELECT COUNT(*) FROM tb_albuns) as total_albuns,
                (SELECT COUNT(*) FROM tb_artistas) as total_artistas,
                (SELECT COUNT(*) FROM tb_gravadoras) as total_gravadoras,
                (SELECT COUNT(*) FROM tb_midias WHERE formato_id = 1) as total_lps,
                (SELECT COUNT(*) FROM tb_midias WHERE formato_id = 2) as total_cds";
        
        return $this->db->query($sql)->fetch(\PDO::FETCH_ASSOC);
    }

    public function buscarUltimasAquisicoes($limit = 6) {
        // YEAR(data_lancamento) resolve a sua necessidade de exibir apenas o ano
        $sql = "SELECT a.album_id as id, a.titulo, art.nome as artista_nome, 
                       g.nome as gravadora_nome, YEAR(a.data_lancamento) as ano_lancamento, 
                       a.capa_url, f.descricao as formato_descricao
                FROM tb_albuns a
                JOIN tb_artistas art ON a.artista_id = art.artista_id
                JOIN tb_midias m ON a.album_id = m.album_id
                JOIN tb_gravadoras g ON m.gravadora_id = g.gravadora_id
                JOIN tb_formatos f ON m.formato_id = f.formato_id
                ORDER BY m.midia_id DESC LIMIT :limit";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}