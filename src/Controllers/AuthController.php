<?php
namespace App\Controllers;

use App\Config\Database;
use PDO;

class AuthController {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function exibirLogin() {
        $erro = $_GET['erro'] ?? null;
        $msg = $_GET['msg'] ?? null;
        
        $mensagemErro = null;
        if ($erro === '1') {
            $mensagemErro = 'E-mail ou senha incorretos.';
        } elseif ($erro === 'fields') {
            $mensagemErro = 'Preencha todos os campos obrigatórios.';
        }
        
        $mensagemSucesso = null;
        if ($msg === 'logout') {
            $mensagemSucesso = 'Sessão encerrada com sucesso.';
        } elseif ($msg === 'cadastrado') {
            $mensagemSucesso = 'Cadastro realizado com sucesso! Faça login abaixo.';
        }

        require_once __DIR__ . '/../Views/auth/login.php';
    }

    public function processarLogin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?url=login');
            exit;
        }

        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';

        if (empty($email) || empty($senha)) {
            header('Location: index.php?url=login&erro=fields');
            exit;
        }

        $stmt = $this->db->prepare("SELECT usuario_id, nome, email, senha FROM tb_usuarios WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario_id'] = (int)$usuario['usuario_id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_email'] = $usuario['email'];

            // Tenta obter papel/role do usuário se a coluna existir
            $role = 'user';
            try {
                $stmtRole = $this->db->prepare("SELECT role FROM tb_usuarios WHERE usuario_id = :id LIMIT 1");
                $stmtRole->execute([':id' => $_SESSION['usuario_id']]);
                $row = $stmtRole->fetch(\PDO::FETCH_ASSOC);
                if ($row && isset($row['role']) && !empty($row['role'])) {
                    $role = $row['role'];
                } else {
                    // fallback para conta curador conhecida
                    if (isset($usuario['email']) && $usuario['email'] === 'curador@soundhaven.com') {
                        $role = 'curador';
                    }
                }
            } catch (\PDOException $e) {
                // coluna 'role' possivelmente inexistente no banco; aplicamos comportamento padrão
                if (isset($usuario['email']) && $usuario['email'] === 'curador@soundhaven.com') {
                    $role = 'curador';
                }
            }

            $_SESSION['usuario_role'] = $role;

            header('Location: index.php?url=dashboard');
            exit;
        }

        header('Location: index.php?url=login&erro=1');
        exit;
    }

    public function exibirCadastro() {
        $erro = $_GET['erro'] ?? null;
        $mensagemErro = null;
        
        if ($erro === 'taken') {
            $mensagemErro = 'Este e-mail já está cadastrado no sistema.';
        } elseif ($erro === 'fields') {
            $mensagemErro = 'Preencha todos os campos obrigatórios.';
        } elseif ($erro === 'db') {
            $mensagemErro = 'Ocorreu um erro ao criar a conta. Tente novamente.';
        }

        require_once __DIR__ . '/../Views/auth/cadastro.php';
    }

    public function processarCadastro() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?url=cadastro');
            exit;
        }

        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';

        if (empty($nome) || empty($email) || empty($senha)) {
            header('Location: index.php?url=cadastro&erro=fields');
            exit;
        }

        // Verifica se e-mail já está cadastrado
        $stmtCheck = $this->db->prepare("SELECT COUNT(*) FROM tb_usuarios WHERE email = :email");
        $stmtCheck->execute([':email' => $email]);
        if ($stmtCheck->fetchColumn() > 0) {
            header('Location: index.php?url=cadastro&erro=taken');
            exit;
        }

        // Insere o novo usuário
            $senhaHash = password_hash($senha, PASSWORD_BCRYPT);
        $sql = "INSERT INTO tb_usuarios (nome, email, senha, role) VALUES (:nome, :email, :senha, :role)";
        $stmtInsert = $this->db->prepare($sql);
        
        try {
            $executou = $stmtInsert->execute([':nome' => $nome, ':email' => $email, ':senha' => $senhaHash, ':role' => 'user']);
        } catch (\PDOException $e) {
            // Se a coluna 'role' ainda não existe, grava sem ela
            if (strpos($e->getMessage(), 'Unknown column') !== false || strpos($e->getMessage(), 'coluna') !== false) {
                $sql = "INSERT INTO tb_usuarios (nome, email, senha) VALUES (:nome, :email, :senha)";
                $stmtInsert = $this->db->prepare($sql);
                $executou = $stmtInsert->execute([':nome' => $nome, ':email' => $email, ':senha' => $senhaHash]);
            } else {
                throw $e;
            }
        }

        if ($executou) {
            // Login automático
            $_SESSION['usuario_id'] = (int)$novoId;
            $_SESSION['usuario_nome'] = $nome;
            $_SESSION['usuario_email'] = $email;
            
            header('Location: index.php?url=dashboard');
            exit;
        }

        header('Location: index.php?url=cadastro&erro=db');
        exit;
    }

    public function logout() {
        // Assegura que a sessão esteja ativa
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // Limpar cookies personalizados
        if (isset($_COOKIE['soundhaven_sugestao_diaria'])) {
            setcookie('soundhaven_sugestao_diaria', '', time() - 3600, '/');
        }

        // Limpar dados de sessão
        $_SESSION = [];
        session_unset();

        // Remover cookie de sessão com os mesmos parâmetros
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            $path = $params['path'] ?: '/';
            $domain = $params['domain'] ?: '';
            $secure = $params['secure'] ?? false;
            $httponly = $params['httponly'] ?? false;
            setcookie(session_name(), '', time() - 42000, $path, $domain, $secure, $httponly);
        } else {
            setcookie(session_name(), '', time() - 42000, '/');
        }

        // Destrói a sessão no servidor
        session_destroy();

        // Garante novo session id para futuras sessões
        if (function_exists('session_regenerate_id')) {
            session_regenerate_id(true);
        }

        header('Location: index.php?url=login&msg=logout');
        exit;
    }
}
