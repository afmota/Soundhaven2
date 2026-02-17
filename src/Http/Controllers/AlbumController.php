<?php

namespace App\Http\Controllers;

use App\Core\Services\AlbumService;
use Exception;

class AlbumController {
    public function __construct(private AlbumService $service) {}

    public function index() {
        $userId = 2; 
        $paginaAtual = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
        $action = $_GET['action'] ?? 'list';

        $filtros = [
            'titulo'   => $_GET['titulo'] ?? '',
            'artista'  => $_GET['artista'] ?? '',
            'tipo'     => $_GET['tipo'] ?? '',
            'situacao' => $_GET['situacao'] ?? ''
        ];

        /**
         * AJUSTE ARQUITETURAL:
         * Se a ação for 'colecao', forçamos o filtro de situação 4.
         * Isso garante que a contagem no MySqlAlbumRepository inclua a "página 12".
         */
        if ($action === 'colecao') {
            $filtros['situacao'] = 4;
        }

        $listaArtistas = $this->service->listarArtistasDoUsuario($userId);

        $itensPorPagina = 25;
        $totalAlbuns = (int) $this->service->contarComFiltros($filtros, $userId);
        $totalPaginas = (int) ceil($totalAlbuns / $itensPorPagina);
        
        // Proteção de limites de página
        if ($paginaAtual > $totalPaginas && $totalPaginas > 0) $paginaAtual = $totalPaginas;
        if ($paginaAtual < 1) $paginaAtual = 1;

        $offset = ($paginaAtual - 1) * $itensPorPagina;

        $albuns = $this->service->listarParaVitrine($filtros, $userId, $itensPorPagina, $offset);

        // Lógica de Range de Paginação
        $range = 2;
        $pagInicio = max(1, $paginaAtual - $range);
        $pagFim = min($totalPaginas, $paginaAtual + $range);

        $queryParams = $_GET;
        unset($queryParams['page']); 
        $urlBase = "?" . http_build_query($queryParams) . "&";

        // Direcionamento integral para a View correta
        if ($action === 'colecao') {
            require_once __DIR__ . '/../../Views/colecao_list.php';
        } else {
            require_once __DIR__ . '/../../Views/album_list.php';
        }
    }

    public function editar() {
        ob_start();
        header('Content-Type: application/json');
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') throw new Exception("Método inválido.");
            $userId = 2; 
            $dados = [
                'id' => filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT),
                'titulo' => filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_SPECIAL_CHARS),
                'capa_url' => filter_input(INPUT_POST, 'capa_url', FILTER_SANITIZE_URL),
                'artista_id' => filter_input(INPUT_POST, 'artista_id', FILTER_VALIDATE_INT),
                'data_lancamento' => $_POST['data_lancamento'] ?? '',
                'tipo_id' => filter_input(INPUT_POST, 'tipo_id', FILTER_VALIDATE_INT),
                'situacao' => filter_input(INPUT_POST, 'situacao', FILTER_VALIDATE_INT)
            ];
            $sucesso = $this->service->atualizarAlbum($dados, $userId);
            ob_clean();
            echo json_encode(['success' => true, 'message' => 'Álbum atualizado!']);
        } catch (Exception $e) {
            ob_clean();
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }

    public function excluir() {
        ob_start();
        header('Content-Type: application/json');
        try {
            $userId = 2;
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            $sucesso = $this->service->excluirAlbum($id, $userId);
            ob_clean();
            echo json_encode(['success' => true, 'message' => 'Álbum descartado!']);
        } catch (Exception $e) {
            ob_clean();
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }

    public function cadastrar() {
        ob_start();
        header('Content-Type: application/json');
        try {
            $userId = 2;
            $dados = [
                'titulo' => filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_SPECIAL_CHARS),
                'capa_url' => filter_input(INPUT_POST, 'capa_url', FILTER_SANITIZE_URL),
                'artista_id' => filter_input(INPUT_POST, 'artista_id', FILTER_VALIDATE_INT),
                'data_lancamento' => $_POST['data_lancamento'] ?? '',
                'tipo_id' => filter_input(INPUT_POST, 'tipo_id', FILTER_VALIDATE_INT),
                'situacao' => filter_input(INPUT_POST, 'situacao', FILTER_VALIDATE_INT)
            ];
            $this->service->salvarNovoAlbum($dados, $userId);
            ob_clean();
            echo json_encode(['success' => true, 'message' => 'Álbum cadastrado!']);
        } catch (Exception $e) {
            ob_clean();
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }

    public function importar() {
        ob_start();
        header('Content-Type: application/json');

        try {
            if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception("Arquivo não enviado ou erro no upload.");
            }

            $userId = 2;
            $file = $_FILES['csv_file']['tmp_name'];
            $handle = fopen($file, "r");
            
            fgetcsv($handle, 1000, ";");

            $sucessos = 0;
            $erros = [];
            $linhaNum = 1;

            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                $linhaNum++;
                
                if (count($data) < 6) {
                    $erros[] = "Linha {$linhaNum}: Colunas insuficientes.";
                    continue;
                }

                $dataOriginal = trim($data[3]);
                $dataProcessada = $dataOriginal;

                if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $dataOriginal)) {
                    $partes = explode('/', $dataOriginal);
                    $dataProcessada = "{$partes[2]}-{$partes[1]}-{$partes[0]}";
                }

                $dados = [
                    'titulo'          => htmlspecialchars($data[0]),
                    'capa_url'        => $data[1],
                    'artista_id'      => (int)$data[2],
                    'data_lancamento' => $dataProcessada,
                    'tipo_id'         => (int)$data[4],
                    'situacao'        => (int)$data[5]
                ];

                try {
                    $this->service->salvarNovoAlbum($dados, $userId);
                    $sucessos++;
                } catch (Exception $e) {
                    $erros[] = "Linha {$linhaNum}: " . $e->getMessage();
                }
            }
            fclose($handle);

            ob_clean();
            echo json_encode([
                'success' => true, 
                'message' => "Processamento concluído: {$sucessos} álbuns importados.",
                'errors'  => $erros
            ]);

        } catch (Exception $e) {
            ob_clean();
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }
}