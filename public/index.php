<?php
require_once __DIR__ . '/../autoload.php';

use App\Controllers\LojaController;

$route = $_GET['url'] ?? 'loja';

switch ($route) {
    case 'loja':
        $controller = new LojaController();
        $controller->index();
        break;

    default:
        http_response_code(404);
        echo "404 - Página não encontrada no SoundHaven";
        break;
}