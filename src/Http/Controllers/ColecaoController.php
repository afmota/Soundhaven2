<?php

namespace App\Http\Controllers;

use App\Core\Services\ColecaoService;
use App\Core\Services\AlbumService;

class ColecaoController {
    public function __construct(
        private ColecaoService $service,
        private AlbumService $albumService
    ) {}

    public function index() {
        $userId = 2; 
        $paginaAtual = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
        $itensPorPagina = 25; 
        
        $filtros = [
            'titulo' => $_GET['titulo'] ?? ''
        ];

        // Dados para selects globais
        $listaArtistas = $this->albumService->listarArtistasDoUsuario($userId);
        $listaTipos = $this->service->getTiposAlbum();
        $listaGravadoras = $this->service->getGravadoras();
        $listaFormatos = $this->service->getFormatos();
        
        // Novas listas N:N
        $listaProdutores = $this->service->getProdutores();
        $listaGeneros = $this->service->getGeneros();
        $listaEstilos = $this->service->getEstilos();

        $totalAlbuns = $this->service->getTotalItens($filtros); 
        $totalPaginas = (int) ceil($totalAlbuns / $itensPorPagina);
        
        if ($paginaAtual > $totalPaginas && $totalPaginas > 0) {
            $paginaAtual = $totalPaginas;
        }

        $offset = ($paginaAtual - 1) * $itensPorPagina;
        $albuns = $this->service->listarColecao($itensPorPagina, $offset, $filtros);

        $range = 2;
        $pagInicio = max(1, $paginaAtual - $range);
        $pagFim = min($totalPaginas, $paginaAtual + $range);

        $queryParams = $_GET;
        unset($queryParams['page']);
        $urlBase = "?" . http_build_query($queryParams) . "&";

        require_once __DIR__ . '/../../Views/colecao_list.php';
    }
}