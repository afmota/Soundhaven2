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
                (SELECT COUNT(*) FROM tb_midias tm INNER JOIN tb_albuns ta ON tm.album_id = ta.album_id) as total_albuns,
                (SELECT COUNT(DISTINCT(art.nome)) FROM tb_midias tm INNER JOIN tb_albuns ta ON tm.album_id = ta.album_id INNER JOIN tb_artistas art ON ta.artista_id = art.artista_id) as total_artistas,
                (SELECT COUNT(DISTINCT(tg.nome)) FROM tb_midias tm INNER JOIN tb_albuns ta ON tm.album_id = ta.album_id INNER JOIN tb_gravadoras tg ON tm.gravadora_id = tg.gravadora_id) as total_gravadoras,
                (SELECT COUNT(*) FROM tb_midias WHERE formato_id = 1) as total_lps,
                (SELECT COUNT(*) FROM tb_midias WHERE formato_id = 2) as total_cds,
                (SELECT (MAX(YEAR(ta.data_lancamento)) - MIN(YEAR(ta.data_lancamento))) FROM tb_midias tm INNER JOIN tb_albuns ta ON tm.album_id = ta.album_id) as total_anos";
        
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

    public function buscarAniversariantesDoDia() {
        $sql = "SELECT a.album_id as id, a.titulo, art.nome as artista_nome, a.capa_url,
                       (YEAR(CURDATE()) - YEAR(a.data_lancamento)) as anos_lancamento,
                       (YEAR(CURDATE()) - YEAR(m.data_aquisicao)) as anos_aquisicao,
                       (DAY(a.data_lancamento) = DAY(CURDATE()) AND MONTH(a.data_lancamento) = MONTH(CURDATE())) as eh_aniversario_lancamento
                FROM tb_albuns a
                JOIN tb_artistas art ON a.artista_id = art.artista_id
                JOIN tb_midias m ON a.album_id = m.album_id
                WHERE (DAY(a.data_lancamento) = DAY(CURDATE()) AND MONTH(a.data_lancamento) = MONTH(CURDATE()))
                   OR (DAY(m.data_aquisicao) = DAY(CURDATE()) AND MONTH(m.data_aquisicao) = MONTH(CURDATE()))";
        
        return $this->db->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }
}