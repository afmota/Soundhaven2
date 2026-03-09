<?php
namespace App\Controllers;

use App\Services\ColecaoService;

class ColecaoController {
    public function index() {
        $service = new ColecaoService();
        $pagina = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;

        $dados = $service->getGridColecao($pagina);

        // Isso cria $albuns, $paginaAtual, $totalPaginas, etc., a partir do array
        extract($dados);

        $accentColor = "#338d33"; 

        include __DIR__ . '/../Views/colecao/grid.php';
    }
}