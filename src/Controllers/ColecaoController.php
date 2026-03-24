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
        $dados = $this->service->getGridColecao($pagina);
        extract($dados);
        include __DIR__ . '/../Views/colecao/grid.php';
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
    
        // 1. Busca os dados do álbum
        $album = $this->service->buscarDetalhesMidia($midia_id);
        
        // 2. BUSCA AS FAIXAS (O que estava faltando!)
        // Use o método que você já tem no Service para listar as faixas
        $faixas = $this->service->getFaixasPorMidia($midia_id);

        // 3. Busca os dicionários para os selects e datalists
        $artistas = $this->service->buscarTodosArtistas();
        $gravadoras = $this->service->buscarTodasGravadoras();
        $tipos = $this->service->buscarTodosTipos();
        $sugestoes = $this->service->listarTodasSugestoes(); 
    
        if (!$album) {
            die("Álbum não encontrado.");
        }
    
        // Agora a View recebe $album, $faixas, $artistas, $gravadoras, $tipos e $sugestoes
        require_once __DIR__ . '/../Views/colecao/editar_album.php';
    }

    public function exibirFormularioInclusao() {
        $album_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        $artistas = $this->service->buscarTodosArtistas();
        $gravadoras = $this->service->buscarTodasGravadoras();
        $formatos = $this->service->buscarTodosFormatos();
        $sugestoes = $this->service->listarTodasSugestoes(); 

        // MUDANÇA AQUI: Se temos um ID, buscamos os dados REAIS do álbum
        if ($album_id) {
            $album = $this->service->buscarPorId($album_id);
        } else {
            $album = []; 
        }

        $faixas = []; 
        require_once __DIR__ . '/../Views/colecao/adquirir_album.php';
    }

    public function salvarEdicao() {
        // Pegamos os IDs ocultos que colocamos no form
        $midiaId = filter_input(INPUT_POST, 'midia_id', FILTER_VALIDATE_INT);
        $albumId = filter_input(INPUT_POST, 'album_id', FILTER_VALIDATE_INT);
    
        if (!$midiaId || !$albumId) {
            die("Erro: IDs de mídia ou álbum não fornecidos.");
        }
    
        // O restante dos dados vem no $_POST
        $dados = $_POST;
    
        // --- CORREÇÃO DO MONSTRO: TRATAMENTO DO PREÇO ---
        if (isset($dados['preco'])) {
            // Troca a vírgula (BR) pelo ponto (SQL)
            $precoLimpo = str_replace(',', '.', $dados['preco']);
            // Remove qualquer caractere que não seja número ou ponto
            $dados['preco'] = filter_var($precoLimpo, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        }
    
        // Chama o Service para processar a transação (Agora com o preço certo!)
        $sucesso = $this->service->atualizarAlbum($midiaId, $dados);
    
        if ($sucesso) {
            // Redireciona de volta para a coleção com mensagem de sucesso
            header("Location: index.php?url=colecao&status=success");
        } else {
            // Caso algo dê errado no banco (Ex: erro de constraint ou SQL)
            header("Location: index.php?url=colecao&status=error");
        }
        exit;
    }

    public function importarDadosDiscogs() {
        // 1. Desliga qualquer exibição de erro que possa sujar a saída
        error_reporting(0);
        ini_set('display_errors', 0);

        // 2. Limpa o buffer de saída (caso algum arquivo tenha dado include com espaço em branco)
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
        exit; // Garante que o PHP pare aqui e não renderize rodapés ou layouts
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
    
        // Tratamento do preço (mesma lógica que usamos na edição)
        if (isset($dados['preco'])) {
            $precoLimpo = str_replace(',', '.', $dados['preco']);
            $dados['preco'] = filter_var($precoLimpo, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        }
    
        // O Service vai cuidar da transação complexa
        $sucesso = $this->service->inserirNovoAlbumNaColecao($dados);
    
        if ($sucesso) {
            header("Location: index.php?url=colecao&status=success&msg=Album+adquirido!");
        } else {
            header("Location: index.php?url=adquirir_album&status=error");
        }
        exit;
    }
}
