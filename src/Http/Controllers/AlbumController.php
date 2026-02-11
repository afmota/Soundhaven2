<?php
namespace App\Http\Controllers;

use App\Core\Services\AlbumService;

class AlbumController {
    public function __construct(private AlbumService $service) {}

    public function index() {
        $userId = 2;
        $paginaAtual = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;

        $filtros = [
            'titulo'   => $_GET['titulo'] ?? '',
            'artista'  => $_GET['artista'] ?? '',
            'tipo'     => $_GET['tipo'] ?? '',
            'situacao' => $_GET['situacao'] ?? ''
        ];

        // Busca a lista de artistas para o dropdown
        $listaArtistas = $this->service->listarArtistasDoUsuario($userId);

        $itensPorPagina = 25;
        $totalAlbuns = $this->service->contarComFiltros($filtros, $userId);
        $totalPaginas = (int) ceil($totalAlbuns / $itensPorPagina);
        $offset = ($paginaAtual - 1) * $itensPorPagina;

        $albuns = $this->service->listarParaVitrine($filtros, $userId, $itensPorPagina, $offset);

        // Paginação
        $range = 2;
        $pagInicio = max(1, $paginaAtual - $range);
        $pagFim = min($totalPaginas, $paginaAtual + $range);

        $queryParams = $_GET;
        unset($queryParams['page']); // Removemos a página para não duplicar
        $urlBase = "?" . http_build_query($queryParams) . "&";

        require_once __DIR__ . '/../../Views/album_list.php';
    }
}