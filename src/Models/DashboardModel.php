<?php

namespace App\Model;

class DashboardModel {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function getEstatisticas($userId) {
        // Exemplo de query consolidada para performance
        $stats = [];
        
        // Contagens básicas
        $stats['total_albuns'] = $this->db->query("SELECT COUNT(*) FROM tb_albuns")->fetchColumn();
        $stats['total_artistas'] = $this->db->query("SELECT COUNT(*) FROM tb_artistas")->fetchColumn();
        $stats['total_gravadoras'] = $this->db->query("SELECT COUNT(*) FROM tb_gravadoras")->fetchColumn();
        
        // Contagens por formato (ajuste os nomes das colunas conforme seu banco)
        $sqlFormatos = "SELECT f.descricao, COUNT(m.midia_id) as total 
                        FROM tb_formatos f 
                        LEFT JOIN tb_midias m ON f.formato_id = m.formato_id 
                        GROUP BY f.formato_id";
        $stats['formatos'] = $this->db->query($sqlFormatos)->fetchAll(\PDO::FETCH_KEY_PAIR);

        // Últimas aquisições
        $sqlUltimos = "SELECT a.album_id as id, a.titulo, art.nome as artista_nome, 
                              g.nome as gravadora_nome, a.ano_lancamento, a.capa_url, f.descricao as formato_descricao
                       FROM tb_albuns a
                       JOIN tb_artistas art ON a.artista_id = art.artista_id
                       JOIN tb_midias m ON a.album_id = m.album_id
                       JOIN tb_gravadoras g ON m.gravadora_id = g.gravadora_id
                       JOIN tb_formatos f ON m.formato_id = f.formato_id
                       ORDER BY m.data_cadastro DESC LIMIT 6";
        $stats['ultimos_albuns'] = $this->db->query($sqlUltimos)->fetchAll();

        return $stats;
    }
}