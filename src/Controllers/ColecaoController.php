<?php
namespace App\Controllers;

use App\Services\ColecaoService;
use App\Config\Database;

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
        $jsonAquisicoes = json_encode($this->service->getDadosGraficoAquisicoes());

        include __DIR__ . '/../Views/colecao/grid.php';
    }

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

    public function salvarVideoFaixa() {
        header('Content-Type: application/json');

        $midiaId = filter_input(INPUT_POST, 'midia_id', FILTER_VALIDATE_INT);
        $numeroFaixa = filter_input(INPUT_POST, 'numero_faixa', FILTER_VALIDATE_INT);
        $videoUrl = trim((string)filter_input(INPUT_POST, 'video_url'));

        if (!$midiaId || !$numeroFaixa) {
            echo json_encode(['success' => false, 'error' => 'Dados da faixa inválidos.']);
            exit;
        }

        $sucesso = $this->service->salvarVideoDaFaixa($midiaId, $numeroFaixa, $videoUrl);
        echo json_encode(['success' => $sucesso]);
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
        $formatos = $this->service->buscarTodosFormatos();
    
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
                    'tracklist' => $resultado['tracklist'],
                    'produtores' => $resultado['produtores'] ?? [],
                    'generos' => $resultado['generos'] ?? [],
                    'estilos' => $resultado['estilos'] ?? []
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
            header("Location: index.php?url=dashboard&status=success&msg=Album+adquirido!");
        } else {
            header("Location: index.php?url=adquirir_album&status=error");
        }
        exit;
    }

    public static function buscarLetraMusica() {
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json; charset=utf-8');
        
        $artista = $_GET['artista'] ?? '';
        $musica = $_GET['mus'] ?? ''; 
        $midiaId = isset($_GET['midia_id']) ? (int)$_GET['midia_id'] : 0;
        $numFaixa = isset($_GET['numero_faixa']) ? (int)$_GET['numero_faixa'] : 0;
        
        if (empty($artista) || empty($musica)) {
            echo json_encode(['status' => 'error', 'message' => 'Parâmetros inválidos.']);
            exit;
        }

        // --- PASSO 1: TENTAR NA API EXTERNA ---
        $url = "https://api.lyrics.ovh/v1/" . urlencode(trim($artista)) . "/" . urlencode(trim($musica));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'SoundHavenApp/1.0');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 6); // Timeout ligeiramente menor para não travar o usuário
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response !== false && $httpCode === 200) {
            $data = json_decode($response, true);
            if (!empty($data['lyrics'])) {
                echo json_encode([
                    'status' => 'success',
                    'origem' => 'api',
                    'lyrics' => $data['lyrics']
                ]);
                exit;
            }
        }

        // --- PASSO 2: FALHOU NA API? TENTA NO BANCO LOCAL ---
        if ($midiaId > 0 && $numFaixa > 0) {
            try {
                // Chama a sua classe de conexão estruturada
                $db = Database::getConnection();
                $stmt = $db->prepare("SELECT texto_letra FROM tb_letras WHERE midia_id = :midia_id AND numero_faixa = :numero_faixa");
                $stmt->execute([
                    'midia_id' => $midiaId,
                    'numero_faixa' => $numFaixa
                ]);
                $letraLocal = $stmt->fetchColumn();

                if ($letraLocal) {
                    echo json_encode([
                        'status' => 'success',
                        'origem' => 'local',
                        'lyrics' => $letraLocal
                    ]);
                    exit;
                }
            } catch (\PDOException $e) {
                // Se der erro no banco, logamos internamente mas não travamos o fluxo visual
                error_log("Erro ao buscar letra local: " . $e->getMessage());
            }
        }

        // --- PASSO 3: NÃO ACHOU EM LUGAR NENHUM ---
        // Devolvemos um sinal claro para o JS abrir o formulário de cadastro
        echo json_encode([
            'status' => 'not_found',
            'message' => 'Letra não encontrada. Deseja cadastrar manualmente?'
        ]);
        exit;
    }

    public static function salvarLetraMusica() {
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json; charset=utf-8');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['status' => 'error', 'message' => 'Método de requisição inválido.']);
            exit;
        }

        $midiaId = isset($_POST['midia_id']) ? (int)$_POST['midia_id'] : 0;
        $numFaixa = isset($_POST['numero_faixa']) ? (int)$_POST['numero_faixa'] : 0;
        $textoLetra = $_POST['texto_letra'] ?? '';

        if ($midiaId <= 0 || $numFaixa <= 0 || empty(trim($textoLetra))) {
            echo json_encode(['status' => 'error', 'message' => 'Dados incompletos para salvamento.']);
            exit;
        }

        try {
            $db = Database::getConnection();
            
            // Usamos ON DUPLICATE KEY UPDATE por segurança para o caso de você decidir editar uma letra existente no futuro
            $stmt = $db->prepare("
                INSERT INTO tb_letras (midia_id, numero_faixa, texto_letra) 
                VALUES (:midia_id, :numero_faixa, :texto_letra)
                ON DUPLICATE KEY UPDATE texto_letra = :texto_letra_update
            ");

            $stmt->execute([
                'midia_id' => $midiaId,
                'numero_faixa' => $numFaixa,
                'texto_letra' => $textoLetra,
                'texto_letra_update' => $textoLetra
            ]);

            echo json_encode(['status' => 'success', 'message' => 'Letra guardada no acervo!']);
            exit;
        } catch (\PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Erro no banco de dados: ' . $e->getMessage()]);
            exit;
        }
    }
}