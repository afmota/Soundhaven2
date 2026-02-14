<?php

namespace App\Infrastructure\Repositories;

use App\Core\Entities\Album;
use PDO;
use Exception;

class MySqlAlbumRepository
{
    public function __construct(private PDO $db) {}

    /**
     * Realiza a exclusão lógica do álbum (Soft Delete)
     */
    public function softDelete(int $id, int $userId): bool
    {
        $sql = "UPDATE tb_albuns SET deletado = 1 WHERE id = :id AND user_id = :user_id";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Atualiza um álbum existente no banco de dados.
     */
    public function update(Album $album, int $userId): bool
    {
        $sql = "UPDATE tb_albuns SET 
                    titulo = :titulo,
                    capa_url = :capa,
                    artista_id = :artista_id,
                    data_lancamento = :data_lancamento,
                    tipo_id = :tipo_id,
                    situacao = :situacao
                WHERE id = :id AND user_id = :user_id AND deletado = 0";

        try {
            $stmt = $this->db->prepare($sql);
            
            $stmt->bindValue(':titulo', $album->getTitulo(), PDO::PARAM_STR);
            $stmt->bindValue(':capa', $album->getCapaUrl(), PDO::PARAM_STR);
            $stmt->bindValue(':artista_id', $album->getArtistaId(), PDO::PARAM_INT);
            $stmt->bindValue(':data_lancamento', $album->getDataLancamento(), PDO::PARAM_STR);
            $stmt->bindValue(':tipo_id', $album->getTipo(), PDO::PARAM_INT);
            $stmt->bindValue(':situacao', $album->getSituacao(), PDO::PARAM_INT);
            $stmt->bindValue(':id', $album->getId(), PDO::PARAM_INT);
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Insere um novo álbum no banco de dados.
     */
    public function create(Album $album, int $userId): int
    {
        $sql = "INSERT INTO tb_albuns (titulo, capa_url, artista_id, user_id, data_lancamento, tipo_id, situacao, deletado, criado_em) 
                VALUES (:titulo, :capa, :artista_id, :user_id, :data_lancamento, :tipo_id, :situacao, 0, NOW())";

        try {
            $stmt = $this->db->prepare($sql);
            
            $stmt->bindValue(':titulo', $album->getTitulo(), PDO::PARAM_STR);
            $stmt->bindValue(':capa', $album->getCapaUrl(), PDO::PARAM_STR);
            $stmt->bindValue(':artista_id', $album->getArtistaId(), PDO::PARAM_INT);
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindValue(':data_lancamento', $album->getDataLancamento(), PDO::PARAM_STR);
            $stmt->bindValue(':tipo_id', $album->getTipo(), PDO::PARAM_INT);
            $stmt->bindValue(':situacao', $album->getSituacao(), PDO::PARAM_INT);

            if ($stmt->execute()) {
                return (int)$this->db->lastInsertId();
            }
            return 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    public function findWithFilters(array $filters, int $userId, int $limit, int $offset): array
    {
        $sql = "SELECT a.id, a.titulo, a.capa_url, a.artista_id, a.data_lancamento, a.tipo_id, a.situacao, a.criado_em,
                       art.nome as artista_nome, t.descricao as tipo_nome
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

        return array_map(fn($row) => new Album(
            (int)$row['id'],
            $row['titulo'],
            $row['capa_url'],
            (int)$row['artista_id'],
            $row['data_lancamento'],
            (int)$row['tipo_id'],
            (int)$row['situacao'],
            $row['artista_nome'],
            $row['criado_em']
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

    /**
     * CORREÇÃO: Busca todos os artistas do usuário, mesmo sem álbuns vinculados.
     */
    public function buscarArtistasPorUsuario(int $userId): array
    {
        // Alterado para buscar diretamente de tb_artistas sem o JOIN restritivo
        $sql = "SELECT id, nome 
                FROM tb_artistas 
                WHERE user_id = :user_id 
                ORDER BY nome ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}