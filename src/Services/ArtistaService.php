<?php
namespace App\Services;

use App\Repositories\ArtistaRepository;

class ArtistaService {
    private $repository;

    public function __construct() {
        $this->repository = new ArtistaRepository();
    }

    public function getGridArtistas($pagina) {
        $limit = 24; // Múltiplo de 2, 3, 4 e 6 para facilitar o grid responsivo
        $offset = ($pagina - 1) * $limit;

        $artistas = $this->repository->buscarArtistasComAlbuns($limit, $offset);
        $totalRegistros = $this->repository->contarTotalArtistasComAlbuns();
        $totalPaginas = ceil($totalRegistros / $limit) ?: 1;

        return [
            'artistas' => $artistas,
            'paginaAtual' => $pagina,
            'totalPaginas' => $totalPaginas,
            'totalRegistros' => $totalRegistros
        ];
    }
}