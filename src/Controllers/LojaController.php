<?php
namespace App\Controllers;

use App\Models\Album;
use App\Models\Artist;
use App\Models\Type;
use App\Models\Situation;

class LojaController {
    public function index() {
        $model = new Album();
        
        // Processamento de Deleção
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            if ($id) {
                $model->softDelete($id);
                $query = $_GET; 
                header("Location: ?" . http_build_query($query));
                exit;
            }
        }

        // Sanitização de Filtros (Garantindo que nunca sejam null)
        $filters = [
            'titulo'      => $_GET['titulo'] ?? '',
            'artista_id'  => $_GET['artista_id'] ?? '',
            'tipo_id'     => $_GET['tipo_id'] ?? '',
            'situacao_id' => $_GET['situacao_id'] ?? '',
        ];

        // Dados para os Selects
        $artistas   = Artist::all();
        $tipos      = Type::all();
        $situacoes  = Situation::all();

        // Paginação Blindada
        $itensPorPagina = 25;
        $paginaAtual = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
        if ($paginaAtual < 1) $paginaAtual = 1;

        $totalAlbuns = $model->getTotalCount($filters);
        $totalPaginas = ceil($totalAlbuns / $itensPorPagina);
        if ($paginaAtual > $totalPaginas && $totalPaginas > 0) $paginaAtual = $totalPaginas;

        $offset = ($paginaAtual - 1) * $itensPorPagina;
        $albuns = $model->getAllPaginated($itensPorPagina, $offset, $filters);

        // Lógica de Links Numéricos (Máximo 5)
        $range = 2;
        $inicioPagina = max(1, $paginaAtual - $range);
        $fimPagina = min($totalPaginas, $paginaAtual + $range);
        
        if ($fimPagina - $inicioPagina < 4) {
            if ($inicioPagina === 1) $fimPagina = min($totalPaginas, 5);
            else $inicioPagina = max(1, $totalPaginas - 4);
        }

        include __DIR__ . '/../Views/loja/grid.php';
    }
}