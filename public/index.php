<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Infrastructure\Database\Connection;
use App\Infrastructure\Repositories\MySqlAlbumRepository;
use App\Core\Services\AlbumService;
use App\Http\Controllers\AlbumController;

$db = Connection::getInstance();
$repository = new MySqlAlbumRepository($db);
$service = new AlbumService($repository);
$controller = new AlbumController($service);

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_SPECIAL_CHARS);

switch ($action) {
    case 'editar':
        $controller->editar();
        break;
    case 'descartar':
        $controller->excluir();
        break;
    case 'cadastrar':
        $controller->cadastrar();
        break;
    default:
        $controller->index();
        break;
}