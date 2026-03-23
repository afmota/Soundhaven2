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

    public function buscarTodosFormatos() {
        return $this->repository->getAllFormatos();
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

            // 1. Dados Básicos (Título, Preço, etc.)
            $this->repository->updateDadosBasicos($midiaId, $dados['album_id'], $dados);

            // 2. Tags N:N (Gêneros, Estilos, Produtores)
            $this->repository->salvarGeneros($dados['album_id'], $dados['generos'] ?? []);
            $this->repository->salvarEstilos($dados['album_id'], $dados['estilos'] ?? []);
            $this->repository->salvarProdutores($dados['album_id'], $dados['produtores'] ?? []);

            // 3. O Chefão Final: As FAIXAS
            $this->repository->salvarFaixas($midiaId, $dados['faixas'] ?? []);

            $this->repository->confirmarTransacao();
            return true;

        } catch (\Exception $e) {
            $this->repository->cancelarTransacao();
            //error_log("Erro Fatal no Soundhaven2: " . $e->getMessage());
            //return false;
            die("Erro no Service: " . $e->getMessage());
        }
    }

    public function buscarPorId($id) {
        // A responsabilidade de saber o SQL é do Repository
        return $this->repository->buscarDetalhesAlbum($id);
    }

public function inserirNovoAlbumNaColecao($dados) {
    try {
        $this->repository->iniciarTransacao();

        // 1. O ID do álbum deve vir do formulário (input hidden) ou da URL
        $albumId = $dados['album_id'] ?? null;
        if (!$albumId) throw new \Exception("ID do álbum não fornecido.");

        // 2. Sincroniza as Tags (Gêneros, Estilos, Produtores) na tb_albuns
        // Usando os métodos que você já tem no Repo e que funcionam na edição
        $this->repository->salvarGeneros($albumId, $dados['generos'] ?? []);
        $this->repository->salvarEstilos($albumId, $dados['estilos'] ?? []);
        $this->repository->salvarProdutores($albumId, $dados['produtores'] ?? []);

        // 3. Insere a nova Mídia na tb_midias e pega o ID gerado
        $midiaId = $this->repository->inserirNovaMidia($dados);

        // 4. Salva as Faixas vinculadas a esta MÍDIA específica
        // O seu repo->salvarFaixas já faz o delete/insert, o que é seguro
        if (!empty($dados['faixas'])) {
            $this->repository->salvarFaixas($midiaId, $dados['faixas']);
        }

        $this->repository->confirmarTransacao();
        return true;

    } catch (\Exception $e) {
        $this->repository->cancelarTransacao();
        error_log("Erro ao adquirir álbum: " . $e->getMessage());
        return false;
    }
}
}