<?php
namespace App\Controllers;

use App\Models\Album;

class LojaController {
    public function index() {
        $model = new Album();
        
        // Lógica de Descarte (Soft Delete)
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            if ($id) {
                $model->softDelete($id);
                header("Location: ?url=loja&page=" . ($_GET['page'] ?? 1));
                exit;
            }
        }

        $itensPorPagina = 25;
        $paginaAtual = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
        if ($paginaAtual < 1) $paginaAtual = 1;
        
        $offset = ($paginaAtual - 1) * $itensPorPagina;
        $albuns = $model->getAllPaginated($itensPorPagina, $offset);
        $totalAlbuns = $model->getTotalCount();
        $totalPaginas = ceil($totalAlbuns / $itensPorPagina);

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