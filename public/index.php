<?php
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
        // Pegamos o ID da mídia da URL (ex: index.php?url=editar_album&midia_id=123)
        $midia_id = $_GET['midia_id'] ?? null;
        $controller = new ColecaoController();
        $controller->exibirFormularioEdicao($midia_id);
        break;

    default:
        http_response_code(404);
        echo "404 - Página não encontrada no SoundHaven";
        break;
}