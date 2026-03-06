<?php
namespace App\Models;

use App\Config\Database;
use PDO;

class Album {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    private function buildFilterQuery($filters) {
        $where = ["a.situacao NOT IN (4, 5)", "a.deletado = 0"];
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

        return ['sql' => implode(" AND ", $where), 'params' => $params];
    }

    public function getAllPaginated($limit, $offset, $filters = []) {
        $filterData = $this->buildFilterQuery($filters);
        
        $sql = "SELECT a.album_id, a.titulo, a.capa_url, a.data_lancamento,
                       a.artista_id, a.gravadora_id, art.nome AS artista_nome, 
                       g.nome AS gravadora_nome, t.descricao AS tipo_desc, 
                       s.descricao AS situacao_desc
                FROM tb_albuns a
                INNER JOIN tb_artistas art ON a.artista_id = art.artista_id
                LEFT JOIN tb_gravadoras g ON a.gravadora_id = g.gravadora_id
                LEFT JOIN tb_tipos t ON a.tipo_id = t.tipo_id
                LEFT JOIN tb_situacoes s ON a.situacao = s.situacao_id
                WHERE {$filterData['sql']}
                ORDER BY a.data_lancamento DESC, a.titulo ASC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        foreach ($filterData['params'] as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalCount($filters = []) {
        $filterData = $this->buildFilterQuery($filters);
        $sql = "SELECT COUNT(*) FROM tb_albuns a WHERE {$filterData['sql']}";
        $stmt = $this->db->prepare($sql);
        foreach ($filterData['params'] as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    // MÉTODO ADICIONADO: Salva as alterações no banco
    public function update($id, $data) {
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
        $stmt->bindValue(':titulo', $data['titulo']);
        $stmt->bindValue(':capa_url', $data['capa_url']);
        $stmt->bindValue(':artista_id', (int) $data['artista_id'], PDO::PARAM_INT);
        $stmt->bindValue(':gravadora_id', $data['gravadora_id'] ? (int) $data['gravadora_id'] : null, PDO::PARAM_INT);
        $stmt->bindValue(':data_lancamento', $data['data_lancamento'] ?: null);
        $stmt->bindValue(':tipo_id', (int) $data['tipo_id'], PDO::PARAM_INT);
        $stmt->bindValue(':situacao', (int) $data['situacao'], PDO::PARAM_INT);
        $stmt->bindValue(':id', (int) $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    public function softDelete($id) {
        $sql = "UPDATE tb_albuns SET deletado = 1, atualizado_em = CURRENT_TIMESTAMP WHERE album_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', (int) $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}