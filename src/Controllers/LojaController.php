<?php
namespace App\Controllers;

use App\Services\AlbumService;
use App\Models\Artist;
use App\Models\Label;
use App\Models\Type;
use App\Models\Situation;

class LojaController {
    public function index() {
        $service = new AlbumService();
        $erro = null;

        // --- 1. PROCESSAMENTO DE AÇÕES (POST) ---
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            
            // AÇÃO: ATUALIZAR
            if ($action === 'update') {
                $id = filter_input(INPUT_POST, 'album_id', FILTER_VALIDATE_INT);
                if ($id && $service->atualizar($id, $_POST)) {
                    header("Location: " . $_SERVER['REQUEST_URI']);
                    exit;
                }
                $erro = "Erro ao atualizar o álbum.";
            }

            // AÇÃO: CRIAR NOVO (A que faltava!)
            if ($action === 'create') {
                if ($service->criarNovoAlbum($_POST)) {
                    header("Location: ?url=loja");
                    exit;
                }
                $erro = "Erro ao cadastrar novo álbum.";
            }

            // AÇÃO: DELETAR
            if ($action === 'delete') {
                $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
                if ($id) {
                    $service->deletar($id);
                    header("Location: ?url=loja");
                    exit;
                }
            }
        }

        // --- 2. PREPARAÇÃO DE DADOS PARA A VIEW (GET) ---
        
        // Filtros vindos da URL
        $filters = [
            'titulo'      => $_GET['titulo'] ?? '',
            'artista_id'  => $_GET['artista_id'] ?? '',
            'tipo_id'     => $_GET['tipo_id'] ?? '',
            'situacao_id' => $_GET['situacao_id'] ?? '',
        ];

        // Paginação e busca de itens via Service
        $paginaAtual = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
        $dadosPagina = $service->getListaPaginada($paginaAtual, $filters);

        // Variáveis para a Grid e Paginação
        $albuns        = $dadosPagina['itens'];
        $totalPaginas  = $dadosPagina['totalPaginas'];
        $paginaAtual   = $dadosPagina['paginaAtual'];
        $inicioPagina  = $dadosPagina['inicioPagina'];
        $fimPagina     = $dadosPagina['fimPagina'];

        // Dados para popular os Selects (Filtros, Edição e Inclusão)
        $artistas   = Artist::all();
        $gravadoras = Label::all();
        $tipos      = Type::all();
        $situacoes  = Situation::all();

        // --- 3. RENDERIZAÇÃO ---
        include __DIR__ . '/../Views/loja/grid.php';
    }
}