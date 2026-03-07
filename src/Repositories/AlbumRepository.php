<?php
namespace App\Repositories;

use App\Config\Database;
use PDO;

class AlbumRepository {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function findPaginated($limit, $offset, array $filters = []) {
        $where = ["a.deletado = 0", "a.situacao NOT IN (4, 5)"];
        $params = [];

        if (!empty($filters['titulo'])) {
            $where[] = "a.titulo LIKE :titulo";
            $params[':titulo'] = "%" . $filters['titulo'] . "%";
        }
        if (!empty($filters['artista_id'])) {
            $where[] = "a.artista_id = :artista_id";
            $params[':artista_id'] = (int) $filters['artista_id'];
        }
        if (!empty($filters['tipo_id'])) {
            $where[] = "a.tipo_id = :tipo_id";
            $params[':tipo_id'] = (int) $filters['tipo_id'];
        }
        if (!empty($filters['situacao_id'])) {
            $where[] = "a.situacao = :situacao_id";
            $params[':situacao_id'] = (int) $filters['situacao_id'];
        }

        $sql = "SELECT a.album_id, a.titulo, a.capa_url, a.data_lancamento,
                       a.artista_id, a.gravadora_id, a.tipo_id, 
                       a.situacao AS situacao_id, 
                       art.nome AS artista_nome, 
                       g.nome AS gravadora_nome, t.descricao AS tipo_desc, 
                       s.descricao AS situacao_desc
                FROM tb_albuns a
                INNER JOIN tb_artistas art ON a.artista_id = art.artista_id
                LEFT JOIN tb_gravadoras g ON a.gravadora_id = g.gravadora_id
                LEFT JOIN tb_tipos t ON a.tipo_id = t.tipo_id
                LEFT JOIN tb_situacoes s ON a.situacao = s.situacao_id
                WHERE " . implode(" AND ", $where) . "
                ORDER BY a.data_lancamento DESC, a.titulo ASC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalCount(array $filters = []) {
        $where = ["a.deletado = 0", "a.situacao NOT IN (4, 5)"];
        $params = [];
        // ... (repetir lógica de filtros do findPaginated aqui para o count)
        
        $sql = "SELECT COUNT(*) FROM tb_albuns a WHERE " . implode(" AND ", $where);
        $stmt = $this->db->prepare($sql);
        // bind parameters...
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public function update($id, array $data) {
        $sql = "UPDATE tb_albuns SET 
                titulo = :titulo, 
                capa_url = :capa_url, 
                artista_id = :artista_id, 
                gravadora_id = :gravadora_id, 
                data_lancamento = :data_lancamento, 
                tipo_id = :tipo_id, 
                situacao = :situacao, 
                atualizado_em = CURRENT_TIMESTAMP
                WHERE album_id = :id";

        $stmt = $this->db->prepare($sql);

        // Vinculando os valores com segurança
        $stmt->bindValue(':id', (int) $id, PDO::PARAM_INT);
        $stmt->bindValue(':titulo', $data['titulo']);
        $stmt->bindValue(':capa_url', $data['capa_url']);
        $stmt->bindValue(':artista_id', (int) $data['artista_id'], PDO::PARAM_INT);

        // Trata gravadora nula (ND)
        $gravadora = !empty($data['gravadora_id']) ? (int)$data['gravadora_id'] : null;
        $stmt->bindValue(':gravadora_id', $gravadora, $gravadora ? PDO::PARAM_INT : PDO::PARAM_NULL);

        $stmt->bindValue(':data_lancamento', $data['data_lancamento'] ?: null);
        $stmt->bindValue(':tipo_id', (int) $data['tipo_id'], PDO::PARAM_INT);
        $stmt->bindValue(':situacao', (int) $data['situacao'], PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function softDelete($id) {
        $sql = "UPDATE tb_albuns SET deletado = 1 WHERE album_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', (int) $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function create(array $data) {
        $sql = "INSERT INTO tb_albuns (titulo, capa_url, artista_id, gravadora_id, data_lancamento, tipo_id, situacao)
                VALUES (:titulo, :capa_url, :artista_id, :gravadora_id, :data_lancamento, :tipo_id, :situacao)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':titulo', $data['titulo']);
        $stmt->bindValue(':capa_url', $data['capa_url'] ?: null);
        $stmt->bindValue(':artista_id', (int)$data['artista_id']);
        $stmt->bindValue(':gravadora_id', $data['gravadora_id'] ? (int)$data['gravadora_id'] : null);
        $stmt->bindValue(':data_lancamento', $data['data_lancamento'] ?: null);
        $stmt->bindValue(':tipo_id', (int)$data['tipo_id']);
        $stmt->bindValue(':situacao', (int)$data['situacao']);
        
        return $stmt->execute();
    }
}