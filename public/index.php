<?php

/**
 * Front Controller - Roteamento Centralizado
 */

require_once __DIR__ . '/../vendor/autoload.php';

use App\Infrastructure\Database\Connection;
use App\Infrastructure\Repositories\MySqlAlbumRepository;
use App\Infrastructure\Repositories\MySqlColecaoRepository;
use App\Core\Services\AlbumService;
use App\Core\Services\ColecaoService;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\ColecaoController;

// Inicialização das Dependências
$db = Connection::getInstance();

// Dependências do Módulo Loja (Álbuns)
$albumRepository = new MySqlAlbumRepository($db);
$albumService = new AlbumService($albumRepository);
$albumController = new AlbumController($albumService);

// Dependências do Módulo Coleção (Injetando AlbumService para acesso a Artistas)
$colecaoRepository = new MySqlColecaoRepository($db);
$colecaoService = new ColecaoService($colecaoRepository);
$colecaoController = new ColecaoController($colecaoService, $albumService);

// Roteamento
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_SPECIAL_CHARS);

switch ($action) {
    case 'colecao':
        $colecaoController->index();
        break;

    case 'editar':
        $albumController->editar();
        break;

    case 'descartar':
        $albumController->excluir();
        break;

    case 'cadastrar':
        $albumController->cadastrar();
        break;

    case 'importar':
        $albumController->importar();
        break;

    case 'cadastrar_artista_rapido':
        header('Content-Type: application/json');
        $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
        if ($nome) {
            try {
                $stmt = $db->prepare("INSERT INTO artistas (nome) VALUES (?)");
                $stmt->execute([$nome]);
                $novoId = $db->lastInsertId();
                echo json_encode(['sucesso' => true, 'id' => $novoId]);
            } catch (Exception $e) {
                echo json_encode(['sucesso' => false, 'mensagem' => $e->getMessage()]);
            }
        } else {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Nome não fornecido']);
        }
        exit;
        break;

    default:
        $albumController->index();
        break;
}