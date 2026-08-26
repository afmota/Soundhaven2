<?php
ob_start();
$sessionTimeout = 30 * 60;

ini_set('session.gc_maxlifetime', (string)$sessionTimeout);
session_set_cookie_params([
    'lifetime' => $sessionTimeout,
    'path' => '/',
    'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();

$sessionExpired = isset($_SESSION['usuario_id'])
    && isset($_SESSION['last_activity'])
    && (time() - (int)$_SESSION['last_activity']) >= $sessionTimeout;

if ($sessionExpired) {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $cookieParams = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $cookieParams['path'], $cookieParams['domain'], $cookieParams['secure'], $cookieParams['httponly']);
    }
    session_destroy();
    session_start();
}

if (isset($_SESSION['usuario_id'])) {
    $_SESSION['last_activity'] = time();
    setcookie(session_name(), session_id(), [
        'expires' => time() + $sessionTimeout,
        'path' => '/',
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
}

require_once __DIR__ . '/../autoload.php';
require_once __DIR__ . '/../src/Config/Database.php'; 

use App\Controllers\LojaController;
use App\Controllers\ColecaoController;
use App\Controllers\ArtistaController; // Importante para o switch

$route = trim((string)($_GET['url'] ?? ''));

// Sem uma rota explícita, preserve a sessão e escolha a página inicial adequada.
if ($route === '') {
    $route = isset($_SESSION['usuario_id']) ? 'dashboard' : 'login';
}

// Guardião de rotas autenticadas (Multiusuário)
$publicRoutes = ['login', 'processar_login', 'cadastro', 'processar_cadastro'];
if (!isset($_SESSION['usuario_id']) && !in_array($route, $publicRoutes)) {
    header("Location: index.php?url=login");
    exit;
}
if (isset($_SESSION['usuario_id']) && in_array($route, $publicRoutes)) {
    header("Location: index.php?url=dashboard");
    exit;
}

$db = new \App\Config\Database(); 
$pdo = $db->getConnection();

switch ($route) {
    case 'dashboard':
        $controller = new App\Controllers\DashboardController($pdo);
        $controller->index();
        break;

    case 'loja':
        $controller = new LojaController();
        $controller->index();
        break;

    case 'novo_album_loja':
        $controller = new LojaController();
        $controller->novoAlbum();
        break;

    case 'colecao':
        $controller = new ColecaoController();
        $controller->index();
        break;

    // --- NOVA ROTA: ARTISTAS ---
    case 'artistas':
        $controller = new ArtistaController();
        $controller->index();
        break;

    case 'novo_artista':
        $controller = new App\Controllers\ArtistaController();
        $controller->novoArtista();
        break;

    case 'salvar_inclusao_artista':
        $controller = new App\Controllers\ArtistaController();
        $controller->salvarInclusaoArtista();
        break;

    case 'registrar_audicao':
        $controller = new ColecaoController();
        $controller->registrarAudicao();
        break;

    case 'buscar_faixas':
        $controller = new ColecaoController();
        $controller->listarFaixas();
        break;

    case 'buscar_letra':
        // Ajuste o nome da classe caso sua classe não se chame ColecaoController
        ColecaoController::buscarLetraMusica();
        break;

    case 'salvar_letra':
        ColecaoController::salvarLetraMusica();
        break;

    case 'salvar_video_faixa':
        $controller = new ColecaoController();
        $controller->salvarVideoFaixa();
        break;
    
    case 'descartar_album':
        $controller = new ColecaoController();
        $controller->descartarAlbum();
        break;

    case 'editar_album':
        $midia_id = $_GET['midia_id'] ?? null;
        $controller = new ColecaoController();
        $controller->exibirFormularioEdicao($midia_id);
        break;

    case 'salvar_edicao':
        $controller = new ColecaoController();
        $controller->salvarEdicao();
        break;

    case 'api_importar_discogs':
        $controller = new App\Controllers\ColecaoController();
        $controller->importarDadosDiscogs();
        break;

    case 'adquirir_album':
        $controller = new App\Controllers\ColecaoController();
        $controller->exibirFormularioInclusao(); 
        break;

    case 'obter_detalhes_album':
        $service = new App\Services\ColecaoService();
        $controller = new App\Controllers\ColecaoController($service);
        $controller->obterDetalhesPorId(); 
        break;

    case 'salvar_inclusao':
        $controller = new App\Controllers\ColecaoController();
        $controller->salvarInclusao();
        break;

    case 'salvar_edicao_artista':
        $controller = new App\Controllers\ArtistaController();
        $controller->salvarEdicaoArtista(); // Este é o método que você precisa criar
        break;

    case 'get_top_artistas_json':
        $controller = new App\Controllers\DashboardController($pdo);
        $controller->getTopArtistasJson();
        break;

    case 'get_top_gravadoras_json':
        $controller = new App\Controllers\DashboardController($pdo);
        $controller->getTopGravadorasJson();
        break;

    case 'buscar_video_youtube':
        $controller = new App\Controllers\DashboardController($pdo);
        $controller->buscarVideoYoutube();
        break;

    case 'perfil':
        $controller = new App\Controllers\PerfilController();
        $controller->index();
        break;

    case 'usuarios':
        $controller = new App\Controllers\UserController();
        $controller->index();
        break;

    case 'promover_usuario':
        $controller = new App\Controllers\UserController();
        $controller->promover();
        break;

    case 'relatorios':
        $controller = new App\Controllers\RelatorioController();
        $controller->index();
        break;

    case 'gerar_relatorio':
        $controller = new App\Controllers\RelatorioController();
        $controller->gerar();
        break;

    case 'login':
        $controller = new App\Controllers\AuthController();
        $controller->exibirLogin();
        break;

    case 'processar_login':
        $controller = new App\Controllers\AuthController();
        $controller->processarLogin();
        break;

    case 'cadastro':
        $controller = new App\Controllers\AuthController();
        $controller->exibirCadastro();
        break;

    case 'processar_cadastro':
        $controller = new App\Controllers\AuthController();
        $controller->processarCadastro();
        break;

    case 'logout':
        $controller = new App\Controllers\AuthController();
        $controller->logout();
        break;

    default:
        http_response_code(404);
        echo "404 - Página não encontrada no SoundHaven";
        break;
}