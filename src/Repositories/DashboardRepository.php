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

    public function buscarUltimasAquisicoes($limit = 5) {
        $sql = "SELECT 
                    a.album_id as id, 
                    m.midia_id, -- Importante para o modal
                    a.titulo, 
                    art.nome as artista_nome, 
                    g.nome as gravadora_nome, 
                    YEAR(a.data_lancamento) as ano_lancamento,
                    a.data_lancamento,
                    m.data_aquisicao,
                    a.capa_url, 
                    f.descricao as formato_nome,
                    f.cor_hex as formato_cor,
                    m.numero_catalogo,
                    m.preco,
                    m.observacoes,
                    -- Agrupando as tags para não precisar de múltiplas queries
                    (SELECT GROUP_CONCAT(tg.descricao SEPARATOR '|') FROM tb_album_generos tga JOIN tb_generos tg ON tga.genero_id = tg.genero_id WHERE tga.album_id = a.album_id) as generos,
                    (SELECT GROUP_CONCAT(te.descricao SEPARATOR '|') FROM tb_album_estilos tea JOIN tb_estilos te ON tea.estilo_id = te.estilo_id WHERE tea.album_id = a.album_id) as estilos,
                    (SELECT GROUP_CONCAT(tp.nome SEPARATOR '|') FROM tb_album_produtores tpa JOIN tb_produtores tp ON tpa.produtor_id = tp.produtor_id WHERE tpa.album_id = a.album_id) as produtores
                FROM tb_albuns a
                JOIN tb_artistas art ON a.artista_id = art.artista_id
                JOIN tb_midias m ON a.album_id = m.album_id
                JOIN tb_gravadoras g ON m.gravadora_id = g.gravadora_id
                JOIN tb_formatos f ON m.formato_id = f.formato_id
                ORDER BY m.data_aquisicao DESC LIMIT :limit";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function buscarAniversariantesDoDia() {
        $sql = "SELECT 
                    a.album_id as id, 
                    m.midia_id, 
                    a.titulo, 
                    art.nome as artista_nome, 
                    g.nome as gravadora_nome,
                    a.capa_url,
                    a.data_lancamento,
                    m.data_aquisicao,
                    m.numero_catalogo,
                    m.preco,
                    m.observacoes,
                    f.descricao as formato_nome,
                    f.cor_hex as formato_cor,
                    -- Cálculo dos anos para o card
                    (YEAR(CURDATE()) - YEAR(a.data_lancamento)) as anos_lancamento,
                    (YEAR(CURDATE()) - YEAR(m.data_aquisicao)) as anos_aquisicao,
                    (DAY(a.data_lancamento) = DAY(CURDATE()) AND MONTH(a.data_lancamento) = MONTH(CURDATE())) as eh_aniversario_lancamento,
                    -- Subqueries para as tags (essenciais para o modal não virar N/D)
                    (SELECT GROUP_CONCAT(tg.descricao SEPARATOR '|') FROM tb_album_generos tga JOIN tb_generos tg ON tga.genero_id = tg.genero_id WHERE tga.album_id = a.album_id) as generos,
                    (SELECT GROUP_CONCAT(te.descricao SEPARATOR '|') FROM tb_album_estilos tea JOIN tb_estilos te ON tea.estilo_id = te.estilo_id WHERE tea.album_id = a.album_id) as estilos,
                    (SELECT GROUP_CONCAT(tp.nome SEPARATOR '|') FROM tb_album_produtores tpa JOIN tb_produtores tp ON tpa.produtor_id = tp.produtor_id WHERE tpa.album_id = a.album_id) as produtores
                FROM tb_albuns a
                JOIN tb_artistas art ON a.artista_id = art.artista_id
                JOIN tb_midias m ON a.album_id = m.album_id
                JOIN tb_gravadoras g ON m.gravadora_id = g.gravadora_id
                JOIN tb_formatos f ON m.formato_id = f.formato_id
                WHERE (DAY(a.data_lancamento) = DAY(CURDATE()) AND MONTH(a.data_lancamento) = MONTH(CURDATE()))
                   OR (DAY(m.data_aquisicao) = DAY(CURDATE()) AND MONTH(m.data_aquisicao) = MONTH(CURDATE()))";
        
        return $this->db->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function buscarTopArtistas($limit = 5) {
        $sql = "SELECT
                    art.artista_id,
                    art.nome as artista,
                    COUNT(m.midia_id) AS total
                FROM tb_midias m
                INNER JOIN tb_albuns a ON m.album_id = a.album_id
                INNER JOIN tb_artistas art ON a.artista_id = art.artista_id
                WHERE art.nome <> 'Vários Artistas'
                GROUP BY art.artista_id, art.nome
                ORDER BY total DESC 
                LIMIT :limit";
    
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function buscarTopGravadoras($limit = 5) {
        $sql = "SELECT
                    tg.gravadora_id,
                    tg.nome AS gravadora,
                    COUNT(tm.gravadora_id) AS total
                FROM tb_midias AS tm
                INNER JOIN tb_gravadoras AS tg ON tm.gravadora_id = tg.gravadora_id
                GROUP BY tg.gravadora_id, tg.nome
                ORDER BY total DESC
                LIMIT :limit";
    
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function buscarTopProdutores($limit = 5) {
        $sql = "SELECT
                    tap.produtor_id,
                    tp.nome as produtor,
                    COUNT(tap.produtor_id) as total
                FROM tb_album_produtores tap
                INNER JOIN tb_produtores tp ON tap.produtor_id = tp.produtor_id
                GROUP BY tap.produtor_id, tp.nome
                ORDER BY total DESC
                LIMIT  :limit";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function buscarTotalPorFormato() {
        $sql = "SELECT 
                    tf.descricao AS formato, 
                    COUNT(tm.midia_id) AS total
                FROM tb_midias tm
                INNER JOIN tb_formatos tf ON tm.formato_id = tf.formato_id
                GROUP BY tf.formato_id, tf.descricao
                ORDER BY total DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function buscarDistribuicaoPorAno() {
        $sql = "SELECT 
                    YEAR(ta.data_lancamento) as ano, 
                    COUNT(*) as total 
                FROM tb_midias tm
                INNER JOIN tb_albuns ta ON tm.album_id = ta.album_id
                WHERE tm.ativo = 1 AND ta.data_lancamento IS NOT NULL
                GROUP BY ano 
                ORDER BY ano ASC";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}