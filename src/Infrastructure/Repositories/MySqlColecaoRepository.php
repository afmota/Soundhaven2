<?php

namespace App\Infrastructure\Repositories;

use PDO;

/**
 * Repositório especializado para operações na Coleção Física.
 */
class MySqlColecaoRepository
{
    public function __construct(private PDO $db) {}

    /**
     * Retorna os itens da coleção física (situacao = 4).
     * Agora suporta filtros dinâmicos.
     */
    public function listarItensColecao(int $limit, int $offset, array $filters = []): array
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
                    g.nome AS gravadora_nome,
                    (SELECT GROUP_CONCAT(gen.descricao SEPARATOR '||') 
                     FROM album_genero ag 
                     JOIN tb_generos gen ON ag.genero_id = gen.id 
                     WHERE ag.album_id = a.id) as generos,
                    (SELECT GROUP_CONCAT(est.descricao SEPARATOR '||') 
                     FROM album_estilo ae 
                     JOIN tb_estilos est ON ae.estilo_id = est.id 
                     WHERE ae.album_id = a.id) as estilos,
                    (SELECT GROUP_CONCAT(CONCAT(af.numero_faixa, '::', af.titulo, '::', IFNULL(af.duracao, '--:--')) ORDER BY af.numero_faixa ASC SEPARATOR '||')
                     FROM album_faixas af 
                     WHERE af.album_id = a.id) as faixas
                FROM
                    tb_albuns a
                    INNER JOIN tb_midia m ON a.id = m.album_id
                    INNER JOIN tb_artistas art ON a.artista_id = art.id
                    LEFT JOIN tb_formatos f ON m.formato_id = f.id
                    LEFT JOIN tb_gravadoras g ON m.gravadora_id = g.id
                WHERE
                    a.situacao = 4";

        $params = [];
        if (!empty($filters['titulo'])) {
            $sql .= " AND a.titulo LIKE :titulo";
            $params[':titulo'] = "%" . $filters['titulo'] . "%";
        }

        $sql .= " ORDER BY m.data_aquisicao DESC, a.criado_em ASC LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * CORREÇÃO: Conta o total de MÍDIAS (sem DISTINCT) para que a página 12 apareça.
     */
    public function contarTotalColecao(array $filters = []): int
    {
        $sql = "SELECT COUNT(*) 
                FROM tb_albuns a
                INNER JOIN tb_midia m ON a.id = m.album_id
                WHERE a.situacao = 4";
        
        $params = [];
        if (!empty($filters['titulo'])) {
            $sql .= " AND a.titulo LIKE :titulo";
            $params[':titulo'] = "%" . $filters['titulo'] . "%";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return (int) $stmt->fetchColumn();
    }
}