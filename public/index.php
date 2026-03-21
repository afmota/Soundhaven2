<?php
ob_start();
require_once __DIR__ . '/../autoload.php';

use App\Controllers\LojaController;
use App\Controllers\ColecaoController;

$route = $_GET['url'] ?? 'loja';

switch ($route) {
    case 'loja':
        $controller = new LojaController();
        $controller->index();
        break;

    case 'colecao':
        $controller = new ColecaoController();
        $controller->index();
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
        $controller->exibirFormularioInclusao(); // Deixa o Controller resolver!
        break;

    case 'obter_detalhes_album':
        $service = new App\Services\ColecaoService();
        $controller = new App\Controllers\ColecaoController($service);
        
        // Este método DEVE existir no seu Controller e dar um echo json_encode()
        $controller->obterDetalhesPorId(); 
        break;

    default:
        http_response_code(404);
        echo "404 - Página não encontrada no SoundHaven";
        break;
}