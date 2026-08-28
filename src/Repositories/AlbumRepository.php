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
        $where = ["a.deletado = 0"];
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

        $sql = "SELECT a.album_id, a.titulo, a.capa_url, a.data_lancamento,
                       a.artista_id, a.gravadora_id, a.tipo_id, 
                       art.nome AS artista_nome, 
                       g.nome AS gravadora_nome, t.descricao AS tipo_desc
                FROM tb_albuns a
                INNER JOIN tb_artistas art ON a.artista_id = art.artista_id
                LEFT JOIN tb_gravadoras g ON a.gravadora_id = g.gravadora_id
                LEFT JOIN tb_tipos t ON a.tipo_id = t.tipo_id
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
        $where = ["a.deletado = 0"];
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
    
        $sql = "SELECT COUNT(*) FROM tb_albuns a WHERE " . implode(" AND ", $where);
        $stmt = $this->db->prepare($sql);
    
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
    
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

        return $stmt->execute();
    }

    public function softDelete($id) {
        $sql = "UPDATE tb_albuns SET deletado = 1 WHERE album_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', (int) $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function create(array $data) {
        $sql = "INSERT INTO tb_albuns (titulo, capa_url, artista_id, gravadora_id, data_lancamento, tipo_id)
                VALUES (:titulo, :capa_url, :artista_id, :gravadora_id, :data_lancamento, :tipo_id)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':titulo', $data['titulo']);
        $stmt->bindValue(':capa_url', $data['capa_url'] ?: null);
        $stmt->bindValue(':artista_id', !empty($data['artista_id']) ? (int)$data['artista_id'] : null, PDO::PARAM_INT);
        $stmt->bindValue(':gravadora_id', $data['gravadora_id'] ? (int)$data['gravadora_id'] : null, $data['gravadora_id'] ? PDO::PARAM_INT : PDO::PARAM_NULL);
        $stmt->bindValue(':data_lancamento', $data['data_lancamento'] ?: null);
        $stmt->bindValue(':tipo_id', !empty($data['tipo_id']) ? (int)$data['tipo_id'] : null, !empty($data['tipo_id']) ? PDO::PARAM_INT : PDO::PARAM_NULL);

        if ($stmt->execute()) {
            return (int)$this->db->lastInsertId();
        }

        return false;
    }

    public function salvarArtistasDoAlbum($albumId, array $artistas) {
        $this->db->prepare("CREATE TABLE IF NOT EXISTS tb_album_artistas (
            album_id INT NOT NULL,
            artista_id INT NOT NULL,
            principal TINYINT(1) NOT NULL DEFAULT 0,
            PRIMARY KEY (album_id, artista_id),
            KEY fk_album_artista_rel_album_idx (album_id),
            KEY fk_album_artista_rel_artista_idx (artista_id),
            CONSTRAINT fk_album_artista_rel_album FOREIGN KEY (album_id) REFERENCES tb_albuns (album_id) ON DELETE CASCADE,
            CONSTRAINT fk_album_artista_rel_artista FOREIGN KEY (artista_id) REFERENCES tb_artistas (artista_id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci")->execute();

        $this->db->prepare("DELETE FROM tb_album_artistas WHERE album_id = :album_id")->execute([':album_id' => $albumId]);

        foreach ($artistas as $indice => $nome) {
            $nome = trim((string)$nome);
            if ($nome === '') {
                continue;
            }

            $stmt = $this->db->prepare("SELECT artista_id FROM tb_artistas WHERE nome = :nome LIMIT 1");
            $stmt->execute([':nome' => $nome]);
            $artistaId = (int)$stmt->fetchColumn();

            if (!$artistaId) {
                $insert = $this->db->prepare("INSERT INTO tb_artistas (nome) VALUES (:nome)");
                $insert->execute([':nome' => $nome]);
                $artistaId = (int)$this->db->lastInsertId();
            }

            $principal = $indice === 0 ? 1 : 0;
            $this->db->prepare("INSERT INTO tb_album_artistas (album_id, artista_id, principal) VALUES (:album_id, :artista_id, :principal)")
                ->execute([
                    ':album_id' => (int)$albumId,
                    ':artista_id' => $artistaId,
                    ':principal' => $principal,
                ]);
        }
    }

    public function buscarOuCriarGravadora($nome) {
        $nome = trim($nome);
        if (empty($nome)) return null;

        // Verifica se já existe
        $stmt = $this->db->prepare("SELECT gravadora_id FROM tb_gravadoras WHERE nome = :nome");
        $stmt->execute([':nome' => $nome]);
        $id = $stmt->fetchColumn();

        if ($id) {
            return (int)$id;
        }

        // Se não existe, cria na hora
        $stmt = $this->db->prepare("INSERT INTO tb_gravadoras (nome) VALUES (:nome)");
        $stmt->execute([':nome' => $nome]);
        
        return (int)$this->db->lastInsertId();
    }
}