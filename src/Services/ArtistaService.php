<?php
namespace App\Services;

use App\Repositories\ArtistaRepository;

class ArtistaService {
    private $repository;

    public function __construct() {
        $this->repository = new ArtistaRepository();
    }

    public function getGridArtistas($pagina, array $filtros = []) {
        $limit = 25;
        $offset = ($pagina - 1) * $limit;

        $artistas = $this->repository->buscarArtistasComAlbuns($limit, $offset, $filtros);
        $totalRegistros = $this->repository->contarTotalArtistasComAlbuns($filtros);
        $totalPaginas = ceil($totalRegistros / $limit) ?: 1;

        return [
            'artistas' => $artistas,
            'paginaAtual' => $pagina,
            'totalPaginas' => $totalPaginas,
            'totalRegistros' => $totalRegistros,
            'fimPagina' => $totalPaginas,
            'inicioPagina' => 1,
            'filters' => $filtros,
            'paises' => $this->repository->buscarPaisesComArtistasComAlbuns(), // Países apenas de artistas com álbum na coleção
            'generos' => $this->repository->buscarTodosGeneros()  // Buscando gêneros para os selects
        ];
    }

    public function atualizarArtista($dados) {
        // Aqui você pode adicionar alguma regra de negócio, 
        // mas por enquanto, vamos repassar direto para o repositório
        return $this->repository->updateArtista($dados);
    }

    public function getDadosParaEdicao() {
        return [
            'paises' => $this->repository->buscarTodosPaises(),
            'generos' => $this->repository->buscarTodosGeneros()
        ];
    }

    public function inserirArtista($dados) {
        return $this->repository->insertArtista($dados);
    }
}