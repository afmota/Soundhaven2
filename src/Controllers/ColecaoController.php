<?php
namespace App\Controllers;

use App\Services\ColecaoService;

class ColecaoController {
    private $service;

    public function __construct($service = null) {
        $this->service = $service ?? new ColecaoService();
    }

public function index() {
    $pagina = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
    
    // Em vez de apenas $_GET, vamos garantir que o gravadora_id seja tratado
    $filtros = $_GET;
    
    // Se o seu formulário lateral usa "gravadora" (nome) e o gráfico usa "id"
    // precisamos garantir que o Repository receba o que ele espera.
    if (isset($_GET['gravadora_id'])) {
        $filtros['gravadora_id'] = $_GET['gravadora_id'];
    }

    $dados = $this->service->getGridColecao($pagina, $filtros);
    
    extract($dados);

    $valorTotal = $this->service->getValorTotalColecao();
    $valorFormatado = 'R$ ' . number_format($valorTotal, 2, ',', '.');
    $maisCaro = $this->service->getDadosAlbumMaisCaro();
    $tempoTotal = $this->service->getTempoTotalFormatado();
    $totalFaixas = $this->service->getTotalFaixas();
    $tempoMedio = $this->service->getTempoMedioFormatado();
    $albumMaisLongo = $this->service->getDadosAlbumMaisLongo();

    include __DIR__ . '/../Views/colecao/grid.php';
}

    // NOVO MÉTODO PARA O BOTÃO DE FONE DE OUVIDO
    public function registrarAudicao() {
        header('Content-Type: application/json');
        $midiaId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$midiaId) {
            echo json_encode(['success' => false, 'error' => 'ID inválido']);
            exit;
        }

        $sucesso = $this->service->marcarComoOuvido($midiaId);
        echo json_encode(['success' => $sucesso]);
        exit;
    }

    public function listarFaixas() {
        header('Content-Type: application/json');
        $midiaId = filter_input(INPUT_GET, 'midia_id', FILTER_VALIDATE_INT);

        if (!$midiaId) {
            echo json_encode(['error' => 'ID inválido']);
            exit;
        }

        $faixas = $this->service->getFaixasPorMidia($midiaId);

        echo json_encode($faixas);
        exit;
    }

    public function descartarAlbum() {
        header('Content-Type: application/json');
        $midiaId = filter_input(INPUT_POST, 'midia_id', FILTER_VALIDATE_INT);

        if (!$midiaId) {
            echo json_encode([
                'success' => false,
                'error' => 'ID inválido'
            ]);
            exit;
        }

        $sucesso = $this->service->desativarMidia($midiaId);

        echo json_encode(['success' => $sucesso]);
        exit;
    }

    public function exibirFormularioEdicao($midia_id) {
        $midia_id = filter_var($midia_id, FILTER_VALIDATE_INT);
    
        if (!$midia_id) {
            header("Location: index.php?url=colecao");
            exit;
        }
    
        $album = $this->service->buscarDetalhesMidia($midia_id);
        $faixas = $this->service->getFaixasPorMidia($midia_id);
        $artistas = $this->service->buscarTodosArtistas();
        $gravadoras = $this->service->buscarTodasGravadoras();
        $tipos = $this->service->buscarTodosTipos();
        $sugestoes = $this->service->listarTodasSugestoes(); 
    
        if (!$album) {
            die("Álbum não encontrado.");
        }
    
        require_once __DIR__ . '/../Views/colecao/editar_album.php';
    }

    public function exibirFormularioInclusao() {
        $album_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        $artistas = $this->service->buscarTodosArtistas();
        $gravadoras = $this->service->buscarTodasGravadoras();
        $formatos = $this->service->buscarTodosFormatos();
        $sugestoes = $this->service->listarTodasSugestoes(); 

        if ($album_id) {
            $album = $this->service->buscarPorId($album_id);
        } else {
            $album = []; 
        }

        $faixas = []; 
        require_once __DIR__ . '/../Views/colecao/adquirir_album.php';
    }

    public function salvarEdicao() {
        $midiaId = filter_input(INPUT_POST, 'midia_id', FILTER_VALIDATE_INT);
        $albumId = filter_input(INPUT_POST, 'album_id', FILTER_VALIDATE_INT);
    
        if (!$midiaId || !$albumId) {
            die("Erro: IDs de mídia ou álbum não fornecidos.");
        }
    
        $dados = $_POST;
    
        if (isset($dados['preco'])) {
            $precoLimpo = str_replace(',', '.', $dados['preco']);
            $dados['preco'] = filter_var($precoLimpo, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        }
    
        $sucesso = $this->service->atualizarAlbum($midiaId, $dados);
    
        if ($sucesso) {
            header("Location: index.php?url=colecao&status=success");
        } else {
            header("Location: index.php?url=colecao&status=error");
        }
        exit;
    }

    public function importarDadosDiscogs() {
        error_reporting(0);
        ini_set('display_errors', 0);

        if (ob_get_length()) ob_clean();

        header('Content-Type: application/json');

        try {
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);

            if (!$data || empty($data['catalogo'])) {
                echo json_encode(['success' => false, 'message' => 'Dados de entrada inválidos.']);
                exit;
            }

            $discogsService = new \App\Services\DiscogsService();
            $resultado = $discogsService->buscarFaixas($data['catalogo'], $data['titulo'] ?? '');

            if ($resultado) {
                echo json_encode([
                    'success' => true,
                    'discogs_id' => $resultado['discogs_id'],
                    'tracklist' => $resultado['tracklist']
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Álbum não encontrado no Service.']);
            }
        } catch (\Throwable $e) {
            ob_clean();
            echo json_encode(['success' => false, 'message' => 'Erro interno: ' . $e->getMessage()]);
            exit;
        }
        exit; 
    }

    public function obterDetalhesPorId() {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'ID ausente ou inválido']);
            exit;
        }

        $dados = $this->service->buscarPorId($id);

        header('Content-Type: application/json');
        echo json_encode($dados);
        exit;
    }

    public function salvarInclusao() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?url=colecao");
            exit;
        }
    
        $dados = $_POST;
    
        if (isset($dados['preco'])) {
            $precoLimpo = str_replace(',', '.', $dados['preco']);
            $dados['preco'] = filter_var($precoLimpo, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        }
    
        $sucesso = $this->service->inserirNovoAlbumNaColecao($dados);
    
        if ($sucesso) {
            header("Location: index.php?url=colecao&status=success&msg=Album+adquirido!");
        } else {
            header("Location: index.php?url=adquirir_album&status=error");
        }
        exit;
    }
}