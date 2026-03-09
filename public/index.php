<?php
require_once __DIR__ . '/../autoload.php';

use App\Controllers\LojaController;
use App\Controllers\ColecaoController; // 1. Importamos o novo Controller

$route = $_GET['url'] ?? 'loja';

switch ($route) {
    case 'loja':
        $controller = new LojaController();
        $controller->index();
        break;

    case 'colecao': // 2. Criamos a rota para a Coleção
        $controller = new ColecaoController();
        $controller->index();
        break;

    default:
        http_response_code(404);
        echo "404 - Página não encontrada no SoundHaven";
        break;
}