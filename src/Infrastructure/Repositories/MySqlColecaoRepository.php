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
     */
    public function listarItensColecao(int $limit, int $offset, array $filters = []): array
    {
        $sql = "SELECT 
                    a.id,
                    a.capa_url,
                    a.titulo,
                    art.id AS artista_id,
                    art.nome AS artista_nome,
                    a.tipo_id,
                    a.data_lancamento,
                    YEAR(a.data_lancamento) AS ano_lancamento,
                    m.data_aquisicao,
                    m.observacoes,
                    m.numero_catalogo,
                    m.gravadora_id,
                    m.formato_id,
                    f.descricao AS formato_nome,
                    g.nome AS gravadora_nome,
                    (SELECT GROUP_CONCAT(p.nome SEPARATOR '||') 
                     FROM album_produtor ap 
                     JOIN tb_produtores p ON ap.produtor_id = p.id 
                     WHERE ap.album_id = a.id) as produtores,
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

    public function listarTodosTipos(): array
    {
        return $this->db->query("SELECT id, descricao FROM tb_tipos ORDER BY descricao ASC")->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function listarTodasGravadoras(): array
    {
        return $this->db->query("SELECT id, nome FROM tb_gravadoras ORDER BY nome ASC")->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function listarTodosFormatos(): array
    {
        return $this->db->query("SELECT id, descricao FROM tb_formatos ORDER BY descricao ASC")->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function listarTodosProdutores(): array
    {
        return $this->db->query("SELECT id, nome FROM tb_produtores ORDER BY nome ASC")->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function listarTodosGeneros(): array
    {
        return $this->db->query("SELECT id, descricao FROM tb_generos ORDER BY descricao ASC")->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function listarTodosEstilos(): array
    {
        return $this->db->query("SELECT id, descricao FROM tb_estilos ORDER BY descricao ASC")->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
}