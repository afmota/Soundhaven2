<?php

namespace App\Http\Controllers;

use App\Core\Services\AlbumService;
use Exception;

class AlbumController {
    public function __construct(private AlbumService $service) {}

    /**
     * Renderiza a vitrine de álbuns
     */
    public function index() {
        $userId = 2; 
        $paginaAtual = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;

        $filtros = [
            'titulo'   => $_GET['titulo'] ?? '',
            'artista'  => $_GET['artista'] ?? '',
            'tipo'     => $_GET['tipo'] ?? '',
            'situacao' => $_GET['situacao'] ?? ''
        ];

        $listaArtistas = $this->service->listarArtistasDoUsuario($userId);

        $itensPorPagina = 25;
        $totalAlbuns = $this->service->contarComFiltros($filtros, $userId);
        $totalPaginas = (int) ceil($totalAlbuns / $itensPorPagina);
        $offset = ($paginaAtual - 1) * $itensPorPagina;

        $albuns = $this->service->listarParaVitrine($filtros, $userId, $itensPorPagina, $offset);

        $range = 2;
        $pagInicio = max(1, $paginaAtual - $range);
        $pagFim = min($totalPaginas, $paginaAtual + $range);

        $queryParams = $_GET;
        unset($queryParams['page']); 
        $urlBase = "?" . http_build_query($queryParams) . "&";

        require_once __DIR__ . '/../../Views/album_list.php';
    }

    /**
     * Processa a edição de um álbum via AJAX
     */
    public function editar() {
        ob_start();
        header('Content-Type: application/json');

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception("Método de requisição inválido.");
            }

            $userId = 2; 

            $dados = [
                'id'               => filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT),
                'titulo'           => filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_SPECIAL_CHARS),
                'capa_url'         => filter_input(INPUT_POST, 'capa_url', FILTER_SANITIZE_URL),
                'artista_id'       => filter_input(INPUT_POST, 'artista_id', FILTER_VALIDATE_INT),
                'data_lancamento'  => $_POST['data_lancamento'] ?? '',
                'tipo_id'          => filter_input(INPUT_POST, 'tipo_id', FILTER_VALIDATE_INT),
                'situacao'         => filter_input(INPUT_POST, 'situacao', FILTER_VALIDATE_INT)
            ];

            if (!$dados['id']) {
                throw new Exception("ID do álbum não fornecido.");
            }

            $sucesso = $this->service->atualizarAlbum($dados, $userId);

            if ($sucesso) {
                ob_clean();
                echo json_encode(['success' => true, 'message' => 'Álbum atualizado com sucesso!']);
            } else {
                throw new Exception("Erro ao persistir no banco de dados.");
            }

        } catch (Exception $e) {
            ob_clean();
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }

    /**
     * Processa a exclusão lógica de um álbum via AJAX
     */
    public function excluir() {
        ob_start();
        header('Content-Type: application/json');

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception("Método de requisição inválido.");
            }

            $userId = 2;
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

            if (!$id) {
                throw new Exception("ID do álbum inválido para exclusão.");
            }

            $sucesso = $this->service->excluirAlbum($id, $userId);

            if ($sucesso) {
                ob_clean();
                echo json_encode(['success' => true, 'message' => 'Álbum descartado com sucesso!']);
            } else {
                throw new Exception("Não foi possível descartar o álbum.");
            }

        } catch (Exception $e) {
            ob_clean();
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }

    /**
     * Processa o cadastro de um novo álbum via AJAX
     */
    public function cadastrar() {
        ob_start();
        header('Content-Type: application/json');

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception("Método de requisição inválido.");
            }

            $userId = 2;

            $dados = [
                'titulo'           => filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_SPECIAL_CHARS),
                'capa_url'         => filter_input(INPUT_POST, 'capa_url', FILTER_SANITIZE_URL),
                'artista_id'       => filter_input(INPUT_POST, 'artista_id', FILTER_VALIDATE_INT),
                'data_lancamento'  => $_POST['data_lancamento'] ?? '',
                'tipo_id'          => filter_input(INPUT_POST, 'tipo_id', FILTER_VALIDATE_INT),
                'situacao'         => filter_input(INPUT_POST, 'situacao', FILTER_VALIDATE_INT)
            ];

            if (empty($dados['titulo']) || !$dados['artista_id']) {
                throw new Exception("Título e Artista são campos obrigatórios.");
            }

            $novoId = $this->service->salvarNovoAlbum($dados, $userId);

            if ($novoId > 0) {
                ob_clean();
                echo json_encode(['success' => true, 'message' => 'Álbum cadastrado com sucesso!']);
            } else {
                throw new Exception("Erro ao cadastrar álbum no banco de dados.");
            }

        } catch (Exception $e) {
            ob_clean();
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }
}