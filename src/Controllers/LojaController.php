<?php
namespace App\Controllers;

use App\Models\Album;
use App\Models\Artist;
use App\Models\Label; // ADICIONADO
use App\Models\Type;
use App\Models\Situation;

class LojaController {
    public function index() {
        $model = new Album();
        
        // Processamento de Edição
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
            $id = filter_input(INPUT_POST, 'album_id', FILTER_VALIDATE_INT);
            if ($id) {
                $data = [
                    'titulo' => $_POST['titulo'],
                    'capa_url' => $_POST['capa_url'],
                    'artista_id' => $_POST['artista_id'],
                    'gravadora_id' => $_POST['gravadora_id'],
                    'data_lancamento' => $_POST['data_lancamento'],
                    'tipo_id' => $_POST['tipo_id'],
                    'situacao' => $_POST['situacao'],
                ];
                $model->update($id, $data);
                header("Location: " . $_SERVER['REQUEST_URI']); 
                exit;
            }
        }

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

        // Sanitização de Filtros
        $filters = [
            'titulo'      => $_GET['titulo'] ?? '',
            'artista_id'  => $_GET['artista_id'] ?? '',
            'tipo_id'     => $_GET['tipo_id'] ?? '',
            'situacao_id' => $_GET['situacao_id'] ?? '',
        ];

        // Dados para os Selects
        $artistas   = Artist::all();
        $gravadoras = Label::all(); // ADICIONADO: Agora a View vai saber o que é $gravadoras
        $tipos      = Type::all();
        $situacoes  = Situation::all();

        // Paginação
        $itensPorPagina = 25;
        $paginaAtual = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
        if ($paginaAtual < 1) $paginaAtual = 1;

        $totalAlbuns = $model->getTotalCount($filters);
        $totalPaginas = ceil($totalAlbuns / $itensPorPagina);
        if ($paginaAtual > $totalPaginas && $totalPaginas > 0) $paginaAtual = $totalPaginas;

        $offset = ($paginaAtual - 1) * $itensPorPagina;
        $albuns = $model->getAllPaginated($itensPorPagina, $offset, $filters);

        // Lógica de Links Numéricos
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