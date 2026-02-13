<?php

/**
 * Front Controller
 * Ponto de entrada único da aplicação.
 */

require_once __DIR__ . '/../vendor/autoload.php';

use App\Infrastructure\Database\Connection;
use App\Infrastructure\Repositories\MySqlAlbumRepository;
use App\Core\Services\AlbumService;
use App\Http\Controllers\AlbumController;

// 1. Inicialização das Dependências (Ajuste conforme sua Connection)
$db = Connection::getInstance();
$repository = new MySqlAlbumRepository($db);
$service = new AlbumService($repository);
$controller = new AlbumController($service);

// 2. Roteamento Simples
// Captura a ação vinda do GET (ex: ?action=editar)
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_SPECIAL_CHARS);

switch ($action) {
    case 'editar':
        // Rota de processamento do formulário (AJAX)
        $controller->editar();
        break;

    default:
        // Rota padrão: Exibição da Vitrine
        $controller->index();
        break;
}