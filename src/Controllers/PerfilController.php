<?php
namespace App\Controllers;

use App\Repositories\DashboardRepository;
use App\Config\Database;

class PerfilController {
    private $repository;

    public function __construct() {
        $db = new Database();
        $this->repository = new DashboardRepository($db->getConnection());
    }

    public function index() {
        $stats = $this->repository->buscarDadosGerais();
        $topGeneros = $this->repository->buscarTopGeneros(3);

        $viewData = [
            'nome' => 'Administrador do Acervo',
            'cargo' => 'Curador de Acervo Musical',
            'email' => 'curador@soundhaven.com',
            'bio' => 'Gestor e apaixonado por colecionar música em formatos físicos. Curador oficial da coleção SoundHaven.',
            'total_albuns' => $stats['total_albuns'] ?? 0,
            'total_artistas' => $stats['total_artistas'] ?? 0,
            'total_lps' => $stats['total_lps'] ?? 0,
            'total_cds' => $stats['total_cds'] ?? 0,
            'top_generos' => $topGeneros
        ];

        $this->render('perfil/index', $viewData);
    }

    private function render($view, $data) {
        extract($data);
        require_once __DIR__ . "/../Views/{$view}.php";
    }
}
