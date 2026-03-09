<?php
namespace App\Repositories;

use App\Config\Database;
use PDO;

class ColecaoRepository {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function buscarParaGrid($limit, $offset) {
        $sql = "SELECT 
                    tm.midia_id,
                    tm.album_id,
                    ta.titulo,
                    ta.capa_url,
                    art.nome AS artista_nome,
                    tg.nome AS gravadora_nome,
                    tf.descricao AS formato_nome,
                    tf.cor_hex AS formato_cor,
                    ta.data_lancamento,
                    tm.data_aquisicao,
                    tm.numero_catalogo,
                    tm.preco,
                    tm.condicao,
                    tm.observacoes
                FROM tb_midias tm
                INNER JOIN tb_albuns ta ON tm.album_id = ta.album_id
                INNER JOIN tb_artistas art ON ta.artista_id = art.artista_id
                INNER JOIN tb_gravadoras tg ON tm.gravadora_id = tg.gravadora_id
                INNER JOIN tb_formatos tf ON tm.formato_id = tf.formato_id
                ORDER BY tm.data_aquisicao DESC
                LIMIT :limit OFFSET :offset";
    
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contarTotal() {
        $sql = "SELECT COUNT(*) 
                FROM tb_albuns a 
                INNER JOIN tb_situacoes s ON a.situacao = s.situacao_id 
                WHERE s.descricao = 'Adquirido'";
                
        return $this->db->query($sql)->fetchColumn();
    }

    public function buscarDetalhesMidia($midiaId) {
        $sql = "SELECT 
                    tm.midia_id, tm.album_id, ta.titulo, ta.capa_url,
                    art.nome AS artista_nome, tg.nome AS gravadora_nome,
                    tf.descricao AS formato_nome, tf.cor_hex AS formato_cor,
                    tm.numero_catalogo, ta.data_lancamento, tm.data_aquisicao,
                    tm.preco, tm.condicao, tm.observacoes
                FROM tb_midias tm
                INNER JOIN tb_albuns ta ON tm.album_id = ta.album_id
                INNER JOIN tb_gravadoras tg ON tm.gravadora_id = tg.gravadora_id
                INNER JOIN tb_formatos tf ON tm.formato_id = tf.formato_id
                INNER JOIN tb_artistas art ON ta.artista_id = art.artista_id
                WHERE tm.midia_id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $midiaId, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}