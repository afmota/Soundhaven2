<?php
namespace App\Controllers;

use App\Services\ColecaoService;

class ColecaoController {
    private $service;

    public function __construct() {
        // Centralizamos a criação do Service aqui
        $this->service = new ColecaoService();
    }

    public function index() {
        $pagina = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;

        // Usando o $this->service que instanciamos no construtor
        $dados = $this->service->getGridColecao($pagina);

        // Isso cria $albuns, $paginaAtual, $totalPaginas, etc.
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
    
        // O Controller pede para o Service, que por sua vez pede para o Repository
        $faixas = $this->service->getFaixasPorMidia($midiaId);
    
        echo json_encode($faixas);
        exit;
    }
}