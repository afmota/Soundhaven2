<?php

namespace App\Infrastructure\Repositories;

use PDO;

/**
 * Repositório especializado para operações na Coleção Física.
 * Responsabilidade: Extração de dados da coleção filtrada por situação no álbum.
 */
class MySqlColecaoRepository
{
    /**
     * @param PDO $db Instância da conexão com o banco de dados.
     */
    public function __construct(private PDO $db) {}

    /**
     * Retorna os itens da coleção física (situacao = 4 no álbum).
     * @param int $limit Quantidade de registros por página.
     * @param int $offset Deslocamento inicial.
     * @return array Lista de álbuns com capa, título, artista, ano, mídia, formato e gravadora.
     */
    public function listarItensColecao(int $limit, int $offset): array
    {
        $sql = "SELECT 
                    a.id,
                    a.capa_url,
                    a.titulo,
                    art.nome AS artista_nome,
                    YEAR(a.data_lancamento) AS ano_lancamento,
                    m.data_aquisicao,
                    m.observacoes,
                    f.descricao AS formato_nome,
                    g.nome AS gravadora_nome
                FROM
                    tb_albuns a
                    INNER JOIN tb_midia m ON a.id = m.album_id
                    INNER JOIN tb_artistas art ON a.artista_id = art.id
                    LEFT JOIN tb_formatos f ON m.formato_id = f.id
                    LEFT JOIN tb_gravadoras g ON m.gravadora_id = g.id
                WHERE
                    a.situacao = 4
                ORDER BY 
                    m.data_aquisicao DESC, 
                    a.criado_em ASC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * Conta o total de itens na coleção física (situacao = 4 na tabela tb_albuns)
     */
    public function contarTotalColecao(): int
    {
        $sql = "SELECT COUNT(DISTINCT a.id) 
                FROM tb_albuns a
                INNER JOIN tb_midia m ON a.id = m.album_id
                WHERE a.situacao = 4";
        
        return (int) $this->db->query($sql)->fetchColumn();
    }
}