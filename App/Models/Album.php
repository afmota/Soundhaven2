<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Album extends Model
{
    protected string $table = 'tb_albuns';

    public function findById(int $id): ?array
    {
        $sql = "
            SELECT 
                id,
                titulo,
                capa_url,
                artista_id,
                data_lancamento,
                tipo_id,
                preco_sugerido,
                situacao,
                criado_em,
                atualizado_em
            FROM {$this->table}
            WHERE id = :id
              AND deletado = 0
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $album = $stmt->fetch(PDO::FETCH_ASSOC);

        return $album ?: null;
    }

    public function all(int $limit = 50, int $offset = 0): array
    {
        $sql = "
            SELECT 
                id,
                titulo,
                capa_url,
                artista_id,
                data_lancamento,
                tipo_id,
                preco_sugerido,
                situacao,
                criado_em
            FROM {$this->table}
            WHERE deletado = 0
            ORDER BY criado_em DESC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): int
    {
        $sql = "
            INSERT INTO {$this->table} (
                titulo,
                capa_url,
                artista_id,
                data_lancamento,
                tipo_id,
                preco_sugerido,
                situacao,
                criado_em
            ) VALUES (
                :titulo,
                :capa_url,
                :artista_id,
                :data_lancamento,
                :tipo_id,
                :preco_sugerido,
                :situacao,
                NOW()
            )
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':titulo'          => $data['titulo'],
            ':capa_url'        => $data['capa_url'] ?? null,
            ':artista_id'      => $data['artista_id'] ?? null,
            ':data_lancamento' => $data['data_lancamento'] ?? null,
            ':tipo_id'         => $data['tipo_id'] ?? null,
            ':preco_sugerido'  => $data['preco_sugerido'] ?? null,
            ':situacao'        => $data['situacao'] ?? null,
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $sql = "
            UPDATE {$this->table}
            SET
                titulo = :titulo,
                capa_url = :capa_url,
                artista_id = :artista_id,
                data_lancamento = :data_lancamento,
                tipo_id = :tipo_id,
                preco_sugerido = :preco_sugerido,
                situacao = :situacao,
                atualizado_em = NOW()
            WHERE id = :id
              AND deletado = 0
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id'              => $id,
            ':titulo'          => $data['titulo'],
            ':capa_url'        => $data['capa_url'] ?? null,
            ':artista_id'      => $data['artista_id'] ?? null,
            ':data_lancamento' => $data['data_lancamento'] ?? null,
            ':tipo_id'         => $data['tipo_id'] ?? null,
            ':preco_sugerido'  => $data['preco_sugerido'] ?? null,
            ':situacao'        => $data['situacao'] ?? null,
        ]);
    }

    public function softDelete(int $id): bool
    {
        $sql = "
            UPDATE {$this->table}
            SET deletado = 1,
                atualizado_em = NOW()
            WHERE id = :id
              AND deletado = 0
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
