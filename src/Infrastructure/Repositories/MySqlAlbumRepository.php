<?php

namespace App\Infrastructure\Repositories;

use App\Core\Entities\Album;
use PDO;

class MySqlAlbumRepository
{
    public function __construct(private PDO $db) {}

    public function findWithFilters(array $filters, int $userId, int $limit, int $offset): array
    {
        // A consulta já traz a.*, garantindo que 'criado_em' esteja no dataset
        $sql = "SELECT a.*, art.nome as artista_nome, t.descricao as tipo_nome
                FROM tb_albuns a
                INNER JOIN tb_artistas art ON a.artista_id = art.id
                INNER JOIN tb_tipos t ON a.tipo_id = t.id
                WHERE a.deletado = 0 AND a.user_id = :user_id";

        $params = [':user_id' => $userId];

        if (!empty($filters['titulo'])) {
            $sql .= " AND a.titulo LIKE :titulo";
            $params[':titulo'] = "%" . $filters['titulo'] . "%";
        }

        if (!empty($filters['artista'])) {
            $sql .= " AND a.artista_id = :artista_id";
            $params[':artista_id'] = (int)$filters['artista'];
        }

        if (!empty($filters['tipo'])) {
            $sql .= " AND a.tipo_id = :tipo_id";
            $params[':tipo_id'] = (int)$filters['tipo'];
        }

        if (!empty($filters['situacao'])) {
            $sql .= " AND a.situacao = :situacao";
            $params[':situacao'] = (int)$filters['situacao'];
        } else {
            $sql .= " AND a.situacao NOT IN (4, 5)";
        }

        $sql .= " ORDER BY a.data_lancamento DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $val) {
            $type = is_int($val) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue($key, $val, $type);
        }

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        // MAPEAMENTO CORRIGIDO: Incluindo o 8º parâmetro (criado_em)
        return array_map(fn($row) => new Album(
            $row['titulo'],
            $row['capa_url'],
            (int)$row['artista_id'],
            $row['data_lancamento'],
            (int)$row['tipo_id'],
            (int)$row['situacao'],
            $row['artista_nome'],
            $row['criado_em'] // Adicionado conforme nova definição da Entidade
        ), $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function countWithFilters(array $filters, int $userId): int
    {
        $sql = "SELECT COUNT(*) 
                FROM tb_albuns a 
                INNER JOIN tb_artistas art ON a.artista_id = art.id
                INNER JOIN tb_tipos t ON a.tipo_id = t.id
                WHERE a.deletado = 0 AND a.user_id = :user_id";

        $params = [':user_id' => $userId];

        if (!empty($filters['titulo'])) {
            $sql .= " AND a.titulo LIKE :titulo";
            $params[':titulo'] = "%" . $filters['titulo'] . "%";
        }

        if (!empty($filters['artista'])) {
            $sql .= " AND a.artista_id = :artista_id";
            $params[':artista_id'] = (int)$filters['artista'];
        }

        if (!empty($filters['tipo'])) {
            $sql .= " AND a.tipo_id = :tipo_id";
            $params[':tipo_id'] = (int)$filters['tipo'];
        }

        if (!empty($filters['situacao'])) {
            $sql .= " AND a.situacao = :situacao";
            $params[':situacao'] = (int)$filters['situacao'];
        } else {
            $sql .= " AND a.situacao NOT IN (4, 5)";
        }

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $val) {
            $type = is_int($val) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue($key, $val, $type);
        }
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public function buscarArtistasPorUsuario(int $userId): array
    {
        $sql = "SELECT DISTINCT art.id, art.nome 
                FROM tb_artistas art
                INNER JOIN tb_albuns a ON art.id = a.artista_id
                WHERE a.user_id = :user_id AND a.deletado = 0
                ORDER BY art.nome ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}