<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Tipo extends Model
{
    protected string $table = 'tb_tipos';

    public function all(): array
    {
        $sql = "
            SELECT
                id,
                descricao
            FROM {$this->table}
            ORDER BY descricao
        ";

        $stmt = $this->db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $sql = "
            SELECT
                id,
                descricao
            FROM {$this->table}
            WHERE id = :id
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $tipo = $stmt->fetch(PDO::FETCH_ASSOC);

        return $tipo ?: null;
    }
}
