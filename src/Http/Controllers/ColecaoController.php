<?php

namespace App\Http\Controllers;

use App\Core\Services\ColecaoService;

class ColecaoController {
    public function __construct(private ColecaoService $service) {}

    public function index() {
        $userId = 2; 
        $paginaAtual = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
        $itensPorPagina = 25; 
        
        // Captura de filtros para que a busca funcione
        $filtros = [
            'titulo' => $_GET['titulo'] ?? ''
        ];

        // Obtém o total considerando os filtros para calcular as páginas corretamente
        // Renomeado para $totalAlbuns para coincidir com a View
        $totalAlbuns = $this->service->getTotalItens($filtros); 
        
        $totalPaginas = (int) ceil($totalAlbuns / $itensPorPagina);
        
        if ($paginaAtual > $totalPaginas && $totalPaginas > 0) {
            $paginaAtual = $totalPaginas;
        }

        $offset = ($paginaAtual - 1) * $itensPorPagina;

        // Passa os filtros para a listagem
        $albuns = $this->service->listarColecao($itensPorPagina, $offset, $filtros);

        // Lógica de Range da Paginação
        $range = 2;
        $pagInicio = max(1, $paginaAtual - $range);
        $pagFim = min($totalPaginas, $paginaAtual + $range);

        // URL Base mantendo os filtros na navegação
        $queryParams = $_GET;
        unset($queryParams['page']);
        $urlBase = "?" . http_build_query($queryParams) . "&";

        require_once __DIR__ . '/../../Views/colecao_list.php';
    }
}