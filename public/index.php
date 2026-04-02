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

    case 'colecao':
        $controller = new ColecaoController();
        $controller->index();
        break;

    // --- NOVA ROTA: ARTISTAS ---
    case 'artistas':
        $controller = new ArtistaController();
        $controller->index();
        break;

    case 'registrar_audicao':
        $controller = new ColecaoController();
        $controller->registrarAudicao();
        break;

    case 'buscar_faixas':
        $controller = new ColecaoController();
        $controller->listarFaixas();
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

    default:
        http_response_code(404);
        echo "404 - Página não encontrada no SoundHaven";
        break;
}