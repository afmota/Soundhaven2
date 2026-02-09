<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Infrastructure\Database\Connection;
use App\Infrastructure\Repositories\MySqlAlbumRepository;
use App\Core\Services\AlbumService;
use App\Http\Controllers\AlbumController;

try {
    // 1. Obtém a conexão correta da sua classe Singleton
    $pdo = Connection::getInstance();

    // 2. Injeção de Dependências correta
    $repository = new MySqlAlbumRepository($pdo);
    $service    = new AlbumService($repository);
    $controller = new AlbumController($service);

    // 3. Roteamento
    $controller->index();

} catch (\Exception $e) {
    // Erro amigável para o usuário, log detalhado no servidor
    die("Desculpe, ocorreu um erro ao carregar a página. Verifique os logs do sistema.");
}