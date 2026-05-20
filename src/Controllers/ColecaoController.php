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
        
        // Pegamos tudo que veio via GET (filtros de gênero, gravadora, formato, etc.)
        $filtros = $_GET;

        if (!empty($_GET['busca'])) {
            $filtros['titulo'] = $_GET['busca'];
        }

        // O service agora recebe o array completo de filtros
        $dados = $this->service->getGridColecao($pagina, $filtros);
        
        extract($dados);
    
        // Se o seu formulário lateral usa "gravadora" (nome) e o gráfico usa "id"
        // precisamos garantir que o Repository receba o que ele espera.
        if (isset($_GET['gravadora_id'])) {
            $filtros['gravadora_id'] = $_GET['gravadora_id'];
        }

        $valorTotal = $this->service->getValorTotalColecao();
        $valorFormatado = 'R$ ' . number_format($valorTotal, 2, ',', '.');
        $maisCaro = $this->service->getDadosAlbumMaisCaro();

        $tempoTotal = $this->service->getTempoTotalFormatado();
        $totalFaixas = $this->service->getTotalFaixas();
        $tempoMedio = $this->service->getTempoMedioFormatado();

        $albumMaisLongo = $this->service->getDadosAlbumMaisLongo();
        $albumMaisCurto = $this->service->getDadosAlbumMaisCurto();

        $estatisticasFaixas = $this->service->estatisticasSimples();
        $maiorMusica = $estatisticasFaixas['maior'];
        $menorMusica = $estatisticasFaixas['menor'];

        $dadosDecadas = $this->service->getDadosGraficoDecadas();
        $jsonDecadas = json_encode($dadosDecadas);

        $jsonDecadas = json_encode($this->service->getDadosGraficoDecadas());
        $jsonAquisicoes = json_encode($this->service->getDadosGraficoAquisicoes());

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

    public function buscarLetraMusica() {
        // Força o PHP a não cuspir nenhum aviso/erro na tela que possa quebrar o JSON do JavaScript
        error_reporting(0);
        ini_set('display_errors', 0);

        header('Content-Type: application/json');
        
        $artistaCru = $_GET['artista'] ?? '';
        $musicaCru = $_GET['mus'] ?? ''; 
        
        if (empty($artistaCru) || empty($musicaCru)) {
            echo json_encode(['type' => 'error', 'message' => 'Parâmetros ausentes.']);
            exit;
        }

        // ==================== O CORTE É AQUI (NOVA URL DA API) ====================
        // Transformamos os nomes em formatos limpos para a URL (tudo minúsculo e sem espaços)
        $artSlug = strtolower(str_replace(' ', '-', trim($artistaCru)));
        $musSlug = strtolower(str_replace(' ', '-', trim($musicaCru)));

        // Remove caracteres especiais que o regex antigo deixaria passar, limpando a string
        $artSlug = preg_replace('/[^a-z0-9\-]/', '', $artSlug);
        $musSlug = preg_replace('/[^a-z0-9\-]/', '', $musSlug);

        // Nova URL oficial da API direta de letras do Vagalume (Evita o erro 503)
        $url = "https://api.vagalume.com.br/www/images/api/api2-musica.php?art={$artSlug}&mus={$musSlug}";
        // ==========================================================================

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        // Configurações cruciais para o ambiente Windows/Local não barrar o cURL por falta de certificados atualizados
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        
        // Adiciona um User-Agent para a API do Vagalume não bloquear a requisição achando que é um bot malicioso
        curl_setopt($ch, CURLOPT_USERAGENT, 'SoundHaven/1.0 (Localhost)');
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if ($response === false || $httpCode !== 200) {
            // Se falhar, pelo menos devolvemos um JSON limpo e válido para o JS não quebrar
            echo json_encode(['type' => 'error', 'message' => 'Erro na conexão com o Vagalume. Código HTTP: ' . $httpCode]);
            exit;
        }

        // Garante que o retorno seja estritamente o JSON do Vagalume
        echo $response;
        exit;
    }
}