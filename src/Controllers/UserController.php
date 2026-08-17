<?php
namespace App\Controllers;

use App\Repositories\UsuarioRepository;

class UserController {
    private $repository;

    public function __construct(?UsuarioRepository $repository = null) {
        $this->repository = $repository ?? new UsuarioRepository();
    }

    private function autorizarCurador() {
        $role = $_SESSION['usuario_role'] ?? 'user';
        if ($role !== 'curador') {
            header('Location: index.php?url=dashboard');
            exit;
        }
    }

    public function index() {
        $this->autorizarCurador();
        $erro = $_GET['erro'] ?? null;
        $msg = $_GET['msg'] ?? null;
        $usuarios = $this->repository->listarUsuarios();
        require_once __DIR__ . '/../Views/usuarios/index.php';
    }

    public function promover() {
        $this->autorizarCurador();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?url=usuarios');
            exit;
        }

        $usuarioId = filter_input(INPUT_POST, 'usuario_id', FILTER_VALIDATE_INT);
        if (!$usuarioId) {
            header('Location: index.php?url=usuarios&erro=invalid');
            exit;
        }

        $usuario = $this->repository->buscarPorId($usuarioId);
        if (!$usuario) {
            header('Location: index.php?url=usuarios&erro=notfound');
            exit;
        }

        if ($usuario['role'] === 'curador') {
            header('Location: index.php?url=usuarios&erro=already');
            exit;
        }

        $sucesso = $this->repository->promoverUsuario($usuarioId);
        if ($sucesso) {
            header('Location: index.php?url=usuarios&msg=promovido');
            exit;
        }

        header('Location: index.php?url=usuarios&erro=failed');
        exit;
    }
}
