<?php

/**
 * Front Controller - Roteamento Atualizado para Suportar Cadastro e Importação
 */

require_once __DIR__ . '/../vendor/autoload.php';

use App\Infrastructure\Database\Connection;
use App\Infrastructure\Repositories\MySqlAlbumRepository;
use App\Core\Services\AlbumService;
use App\Http\Controllers\AlbumController;

// Inicialização das Dependências (Mantendo seu padrão Singleton)
$db = Connection::getInstance();
$repository = new MySqlAlbumRepository($db);
$service = new AlbumService($repository);
$controller = new AlbumController($service);

// Roteamento
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_SPECIAL_CHARS);

switch ($action) {
    case 'editar':
        $controller->editar();
        break;

    case 'descartar':
        $controller->excluir();
        break;

    case 'cadastrar': // Rota necessária para inclusão individual
        $controller->cadastrar();
        break;

    case 'importar': // Rota necessária para importação CSV
        $controller->importar();
        break;

    default:
        $controller->index();
        break;
}