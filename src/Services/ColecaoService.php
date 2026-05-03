<?php
namespace App\Services;

use App\Repositories\ColecaoRepository;

class ColecaoService {

    private $repository;

    public function __construct() {
        $this->repository = new ColecaoRepository();
    }

    public function getGridColecao($pagina, $filtros = []) {

        $limit = 25;
        $offset = ($pagina - 1) * $limit;

        // Repassamos os filtros para a busca dos itens e para a contagem total
        $itens = $this->repository->buscarParaGrid($limit, $offset, $filtros);
        $totalRegistros = $this->repository->contarTotal($filtros);

        $totalPaginas = ceil($totalRegistros / $limit) ?: 1;

        $maxLinks = 2;

        $inicioPagina = max(1, $pagina - $maxLinks);
        $fimPagina = min($totalPaginas, $pagina + $maxLinks);

        // Retornamos também os filtros para que a View possa manter os estados nos inputs
        return [
            'albuns' => $itens,
            'paginaAtual' => $pagina,
            'totalPaginas' => $totalPaginas,
            'inicioPagina' => $inicioPagina,
            'fimPagina' => $fimPagina,
            'totalRegistros' => $totalRegistros,
            'filters' => $filtros,
            // Adicionamos os dados necessários para popular os selects do sidebar
            'artistas' => $this->buscarTodosArtistas(),
            'gravadoras' => $this->buscarTodasGravadoras(),
            'tipos' => $this->buscarTodosTipos(),
            'situacoes' => $this->repository->getAllSituacoes() 
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

    /**
     * ATUALIZAR ÁLBUM - Agora com tratamento de gravadora dinâmica
     */
    public function atualizarAlbum($midiaId, $dados) {
        try {
            $this->repository->iniciarTransacao();

            // --- TRATAMENTO DA GRAVADORA DINÂMICA NA EDIÇÃO ---
            if (!empty($dados['gravadora_nome'])) {
                $idGravadora = $this->repository->buscarOuCriarGravadora($dados['gravadora_nome']);
                $dados['gravadora_id'] = $idGravadora;
            }

            // 1. Dados Básicos
            $this->repository->updateDadosBasicos($midiaId, $dados['album_id'], $dados);

            // 2. Tags N:N
            $this->repository->salvarGeneros($dados['album_id'], $dados['generos'] ?? []);
            $this->repository->salvarEstilos($dados['album_id'], $dados['estilos'] ?? []);
            $this->repository->salvarProdutores($dados['album_id'], $dados['produtores'] ?? []);

            // 3. Faixas
            $this->repository->salvarFaixas($midiaId, $dados['faixas'] ?? []);

            $this->repository->confirmarTransacao();
            return true;

        } catch (\Exception $e) {
            $this->repository->cancelarTransacao();
            error_log("Erro no atualizarAlbum: " . $e->getMessage());
            die("Erro no Service: " . $e->getMessage());
        }
    }

    public function buscarPorId($id) {
        return $this->repository->buscarDetalhesAlbum($id);
    }

    /**
     * INSERIR NOVO ÁLBUM - Mantido com a lógica de gravadora dinâmica
     */
    public function inserirNovoAlbumNaColecao($dados) {
        try {
            $this->repository->iniciarTransacao();

            $albumId = (int)$dados['album_id'];

            // --- TRATAMENTO DA GRAVADORA DINÂMICA ---
            if (!empty($dados['gravadora_nome'])) {
                $idGravadora = $this->repository->buscarOuCriarGravadora($dados['gravadora_nome']);
                $dados['gravadora_id'] = $idGravadora;
            }

            // 1. Sincroniza Tags do Álbum
            $this->repository->salvarGeneros($albumId, $dados['generos'] ?? []);
            $this->repository->salvarEstilos($albumId, $dados['estilos'] ?? []);
            $this->repository->salvarProdutores($albumId, $dados['produtores'] ?? []);

            // 2. Insere a Mídia
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

    public function marcarComoOuvido($midiaId) {
        return $this->repository->registrarExecucao($midiaId);
    }

    public function getValorTotalColecao() {
        return $this->repository->getValorTotalColecao();
    }

    public function getTempoTotalFormatado() {
        $segundosTotais = $this->repository->getTempoTotalColecao();

        $horas = floor($segundosTotais / 3600);
        $minutos = floor(($segundosTotais % 3600) / 60);
        $segundos = $segundosTotais % 60;

        // Retorna no formato: 125h 40m 15s
        return sprintf("%dh %02dm %02ds", $horas, $minutos, $segundos);
    }

    public function getTotalFaixas() {
        return $this->repository->getTotalFaixasColecao();
    }

    public function getTempoMedioFormatado() {
        $segundosMedios = $this->repository->getTempoMedioFaixas();

        // Transformamos o float em int explicitamente para calar o aviso
        $segundosInteiros = (int)round($segundosMedios);

        $minutos = floor($segundosInteiros / 60);
        $segundos = $segundosInteiros % 60;

        return sprintf("%02d:%02d", $minutos, $segundos);
    }

    public function getDadosAlbumMaisCaro() {
        $dados = $this->repository->getAlbumMaisCaro();
        
        if (!$dados) {
            return ['titulo' => 'N/A', 'preco' => 'R$ 0,00'];
        }

        return [
            'titulo' => $dados['titulo'],
            'preco' => 'R$ ' . number_format($dados['preco'], 2, ',', '.')
        ];
    }
}