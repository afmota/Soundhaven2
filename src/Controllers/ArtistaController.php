<?php
namespace App\Controllers;

use App\Services\ArtistaService;

class ArtistaController {
    private $service;

    public function __construct() {
        $this->service = new ArtistaService();
    }

    public function index() {
        $pagina = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
        $dados = $this->service->getGridArtistas($pagina);
        
        extract($dados);
        include __DIR__ . '/../Views/artistas/grid.php';
    }
}