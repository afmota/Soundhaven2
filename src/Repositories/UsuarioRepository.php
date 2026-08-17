<?php
namespace App\Repositories;

use App\Config\Database;
use PDO;

class UsuarioRepository {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function listarUsuarios(): array {
        $sql = "SELECT usuario_id, nome, email, role FROM tb_usuarios ORDER BY nome ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function promoverUsuario(int $usuarioId): bool {
        $sql = "UPDATE tb_usuarios SET role = 'curador' WHERE usuario_id = :id AND role <> 'curador'";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $usuarioId]);
    }

    public function buscarPorId(int $usuarioId): ?array {
        $sql = "SELECT usuario_id, nome, email, role FROM tb_usuarios WHERE usuario_id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $usuarioId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
}
