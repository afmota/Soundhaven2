<?php
namespace App\Controllers;

use App\Models\Album;

class LojaController {
    public function index() {
        $model = new Album();
        
        $itensPorPagina = 25;
        $paginaAtual = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
        if ($paginaAtual < 1) $paginaAtual = 1;
        
        $offset = ($paginaAtual - 1) * $itensPorPagina;
        $albuns = $model->getAllPaginated($itensPorPagina, $offset);
        $totalAlbuns = $model->getTotalCount();
        $totalPaginas = ceil($totalAlbuns / $itensPorPagina);

        // Lógica de Janela de Paginação (Máximo 5 links)
        $range = 2;
        $inicioPagina = max(1, $paginaAtual - $range);
        $fimPagina = min($totalPaginas, $paginaAtual + $range);

        // Ajuste para garantir 5 links quando possível
        if ($fimPagina - $inicioPagina < 4) {
            if ($inicioPagina === 1) {
                $fimPagina = min($totalPaginas, 5);
            } else {
                $inicioPagina = max(1, $totalPaginas - 4);
            }
        }

        include __DIR__ . '/../Views/loja/grid.php';
    }
}