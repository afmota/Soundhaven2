<?php
namespace App\Controllers;

use App\Services\AlbumService; // Mudamos de Model para Service
use App\Models\Artist;
use App\Models\Label;
use App\Models\Type;
use App\Models\Situation;

class LojaController {
    public function index() {
        $service = new AlbumService();
        
        // POST: Ações de Escrita
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            
            if ($action === 'update') {
                $id = filter_input(INPUT_POST, 'album_id', FILTER_VALIDATE_INT);
                $service->atualizar($id, $_POST);
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit;
            }

            if ($action === 'delete') {
                $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
                $service->deletar($id);
                header("Location: ?url=loja");
                exit;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update') {
            $id = filter_input(INPUT_POST, 'album_id', FILTER_VALIDATE_INT);
            
            if ($id && $service->salvarEdicao($id, $_POST)) {
                // Sucesso! Recarrega a página para ver as mudanças
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit;
            } else {
                $erro = "Erro ao salvar as alterações.";
            }
        }

        // GET: Preparação da View
        $filters = [
            'titulo'      => $_GET['titulo'] ?? '',
            'artista_id'  => $_GET['artista_id'] ?? '',
            'tipo_id'     => $_GET['tipo_id'] ?? '',
            'situacao_id' => $_GET['situacao_id'] ?? '',
        ];

        $paginaAtual = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
        $dadosPagina = $service->getListaPaginada($paginaAtual, $filters);

        // Dados para selects (Partials)
        $artistas   = Artist::all();
        $gravadoras = Label::all();
        $tipos      = Type::all();
        $situacoes  = Situation::all();

        // Variáveis para a View
        $albuns = $dadosPagina['itens'];
        $totalPaginas = $dadosPagina['paginas'];

        include __DIR__ . '/../Views/loja/grid.php';
    }
}