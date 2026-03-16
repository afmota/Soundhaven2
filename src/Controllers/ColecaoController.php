<?php
namespace App\Controllers;

use App\Services\ColecaoService;

class ColecaoController {
    private $service;

    public function __construct() {
        $this->service = new ColecaoService();
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
    
        // 1. O Service busca o álbum específico
        $album = $this->service->buscarDetalhesMidia($midia_id);
        
        // 2. O Service busca as listas para alimentar os selects da View
        $artistas = $this->service->buscarTodosArtistas();
        $gravadoras = $this->service->buscarTodasGravadoras();
        $tipos = $this->service->buscarTodosTipos();

        // --- O "CANTO" PARA O NOVO CÓDIGO ---
        // Aqui buscamos o "dicionário" para os datalists das tags N:N
        $sugestoes = $this->service->listarTodasSugestoes(); 
        // ------------------------------------
    
        if (!$album) {
            die("Álbum não encontrado.");
        }
    
        // 3. A View recebe o álbum, os selects e agora as $sugestoes
        require_once __DIR__ . '/../Views/colecao/editar_album.php';
    }

public function salvarEdicao() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: index.php?url=colecao");
        exit;
    }

    $midiaId = filter_input(INPUT_POST, 'midia_id', FILTER_VALIDATE_INT);
    $dados = $_POST;

    // A Mágica da Validação: Troca vírgula por ponto e garante que seja um float
    if (isset($dados['preco'])) {
        $precoLimpo = str_replace(',', '.', $dados['preco']);
        $dados['preco'] = filter_var($precoLimpo, FILTER_VALIDATE_FLOAT);
        
        // Se o valor for inválido ou negativo, a gente reseta para 0 ou trata o erro
        if ($dados['preco'] === false || $dados['preco'] < 0) {
            $dados['preco'] = 0.00;
        }
    }

    $sucesso = $this->service->atualizarAlbum($midiaId, $dados);

    if ($sucesso) {
        header("Location: index.php?url=colecao&status=success");
    } else {
        // Seria bom passar uma mensagem de erro mais específica aqui
        header("Location: index.php?url=colecao&status=error");
    }
    exit;
}
}