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

            $albumId = (int)$dados['album_id'];

            // --- TRATAMENTO DA GRAVADORA DINÂMICA ---
            // Se veio um nome de gravadora, precisamos de um ID
            if (!empty($dados['gravadora_nome'])) {
                // Este método novo no Repo cuida de achar ou criar
                $idGravadora = $this->repository->buscarOuCriarGravadora($dados['gravadora_nome']);

                // SOBRESCREVEMOS o gravadora_id no array de dados para o Repo usar
                $dados['gravadora_id'] = $idGravadora;
            }

            // 1. Sincroniza Tags do Álbum (Gêneros, etc)
            $this->repository->salvarGeneros($albumId, $dados['generos'] ?? []);
            $this->repository->salvarEstilos($albumId, $dados['estilos'] ?? []);
            $this->repository->salvarProdutores($albumId, $dados['produtores'] ?? []);

            // 2. Insere a Mídia usando o $dados['gravadora_id'] que acabamos de garantir
            $midiaId = $this->repository->inserirNovaMidia($dados);

            // 3. Faixas e Status
            if (!empty($dados['faixas'])) {
                $this->repository->salvarFaixas($midiaId, $dados['faixas']);
            }
            $this->repository->atualizarStatusAlbum($albumId, 4);

            $this->repository->confirmarTransacao();
            return true;

        } catch (\Exception $e) {
            $this->repository->cancelarTransacao();
            error_log("Erro na aquisição: " . $e->getMessage());
            return false;
        }
    }
}