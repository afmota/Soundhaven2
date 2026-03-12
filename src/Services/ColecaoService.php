<?php
namespace App\Services;

use App\Repositories\ColecaoRepository;

class ColecaoService {
    private $repository;

    public function __construct() {
        $this->repository = new ColecaoRepository();
    }

    public function getGridColecao($pagina) {
        $limit = 25;
        $offset = ($pagina - 1) * $limit;

        $itens = $this->repository->buscarParaGrid($limit, $offset);
        $totalRegistros = $this->repository->contarTotal();
        $totalPaginas = ceil($totalRegistros / $limit) ?: 1;

        // Lógica de janelas de página para o partial de paginação
        $maxLinks = 2;
        $inicioPagina = max(1, $pagina - $maxLinks);
        $fimPagina = min($totalPaginas, $pagina + $maxLinks);

        return [
            'albuns' => $itens,
            'paginaAtual' => $pagina,
            'totalPaginas' => $totalPaginas,
            'inicioPagina' => $inicioPagina,
            'fimPagina' => $fimPagina,
            'totalRegistros' => $totalRegistros
        ];
    }

    public function getFaixasPorMidia($midiaId) {
        // Aqui o Service chama o método do Repository
        // Certifique-se de que o seu Service tenha o $this->repository instanciado no construtor dele
        return $this->repository->buscarFaixasPorMidia($midiaId);
    }
}