<?php

namespace App\Http\Controllers;

use App\Core\Services\ColecaoService;

class ColecaoController {
    public function __construct(private ColecaoService $service) {}

    public function index() {
        $userId = 2; 
        $paginaAtual = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
        $itensPorPagina = 25; // Garante o grid 5x5
        
        $totalItens = $this->service->getTotalItens();
        $totalPaginas = (int) ceil($totalItens / $itensPorPagina);
        $offset = ($paginaAtual - 1) * $itensPorPagina;

        $albuns = $this->service->listarColecao($itensPorPagina, $offset);

        // Lógica de Range da Paginação
        $range = 2;
        $pagInicio = max(1, $paginaAtual - $range);
        $pagFim = min($totalPaginas, $paginaAtual + $range);

        // URL Base para os links de página
        $urlBase = "?action=colecao&";

        require_once __DIR__ . '/../../Views/colecao_list.php';
    }
}