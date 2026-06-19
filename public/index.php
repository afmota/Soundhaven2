<?php
ob_start();
require_once __DIR__ . '/../autoload.php';
require_once __DIR__ . '/../src/Config/Database.php'; 

use App\Controllers\LojaController;
use App\Controllers\ColecaoController;
use App\Controllers\ArtistaController; // Importante para o switch

$route = $_GET['url'] ?? 'dashboard';
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

    case 'loja_desejar_album':
        $controller = new LojaController();
        $controller->moverParaWishlist();
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

    case 'configuracao':
        $controller = new App\Controllers\ConfiguracaoController();
        $controller->index();
        break;

    case 'configuracao/backup':
        $controller = new App\Controllers\ConfiguracaoController();
        $controller->backup();
        break;

    case 'configuracao/restaurar':
        $controller = new App\Controllers\ConfiguracaoController();
        $controller->restaurar();
        break;

    case 'perfil':
        $controller = new App\Controllers\PerfilController();
        $controller->index();
        break;

    case 'logout':
        // Simulação de logout limpando cookies e redirecionando de volta ao dashboard
        if (isset($_COOKIE['soundhaven_sugestao_diaria'])) {
            setcookie('soundhaven_sugestao_diaria', '', time() - 3600, '/');
        }
        header("Location: index.php?url=dashboard&msg=logout_sucesso");
        exit;

    default:
        http_response_code(404);
        echo "404 - Página não encontrada no SoundHaven";
        break;
}