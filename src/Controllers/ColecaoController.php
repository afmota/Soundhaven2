<?php
namespace App\Controllers;

use App\Services\ColecaoService;

class ColecaoController {
    private $service;

    public function __construct() {
        $this->service = new ColecaoService();
    }

    public function index() {
        $pagina = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
        $dados = $this->service->getGridColecao($pagina);
        extract($dados);
        $accentColor = "#338d33"; 
        include __DIR__ . '/../Views/colecao/grid.php';
    }

    public function listarFaixas() {
        header('Content-Type: application/json');
        $midiaId = filter_input(INPUT_GET, 'midia_id', FILTER_VALIDATE_INT);
        if (!$midiaId) {
            echo json_encode(['error' => 'ID da mídia inválido']);
            exit;
        }
        $faixas = $this->service->getFaixasPorMidia($midiaId);
        echo json_encode($faixas);
        exit;
    }

    public function descartarAlbum() {
        header('Content-Type: application/json');
        $midiaId = filter_input(INPUT_POST, 'midia_id', FILTER_VALIDATE_INT);

        if (!$midiaId) {
            echo json_encode(['success' => false, 'error' => 'ID inválido']);
            exit;
        }

        $sucesso = $this->service->desativarMidia($midiaId);
        echo json_encode(['success' => $sucesso]);
        exit;
    }
}