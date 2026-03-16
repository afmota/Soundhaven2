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
        return $this->repository->buscarFaixasPorMidia($midiaId);
    }

    public function desativarMidia($midiaId) {
        return $this->repository->marcarComoInativo($midiaId);
    }

    public function buscarDetalhesMidia($midiaId) {
        return $this->repository->buscarDetalhesMidia($midiaId);
    }

    public function buscarTodasGravadoras() {
        return $this->repository->buscarTodasGravadoras();
    }

    public function buscarTodosArtistas() {
        return $this->repository->getAllArtistas();
    }
    
    public function buscarTodosTipos() {
        return $this->repository->getAllTipos();
    }

    public function listarTodasSugestoes() {
        return [
            'generos'    => $this->repository->getAllGeneros(),
            'estilos'    => $this->repository->getAllEstilos(),
            'produtores' => $this->repository->getAllProdutores()
        ];
    }

public function atualizarAlbum($midiaId, $dados) {
    try {
        $this->repository->iniciarTransacao();

        // 1. Salva Título, Datas e o PREÇO
        $this->repository->updateDadosBasicos($midiaId, $dados['album_id'], $dados);

        // 2. Sincroniza Gêneros, Estilos e Produtores (os N:N que já fizemos)
        $this->repository->salvarGeneros($dados['album_id'], $dados['generos'] ?? []);
        $this->repository->salvarEstilos($dados['album_id'], $dados['estilos'] ?? []);
        $this->repository->salvarProdutores($dados['album_id'], $dados['produtores'] ?? []);

        $this->repository->confirmarTransacao();
        return true;
    } catch (\Exception $e) {
        $this->repository->cancelarTransacao();
        error_log("Erro no Update: " . $e->getMessage());
        return false;
    }
}
}