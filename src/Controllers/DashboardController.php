<?php
namespace App\Controllers;

use App\Repositories\DashboardRepository;

class DashboardController {
    private $repository;

    public function __construct($pdo) {
        $this->repository = new DashboardRepository($pdo);
    }

    public function index() {
        // 1. Remova o $_SESSION daqui. O Repository vai buscar tudo do banco.
        $stats = $this->repository->buscarDadosGerais(); 
        $ultimos = $this->repository->buscarUltimasAquisicoes(6);

        $viewData = [
            'total_albuns'     => $stats['total_albuns'] ?? 0,
            'total_lps'        => $stats['total_lps'] ?? 0,
            'total_cds'        => $stats['total_cds'] ?? 0,
            'total_artistas'   => $stats['total_artistas'] ?? 0,
            'total_gravadoras' => $stats['total_gravadoras'] ?? 0,
            'ultimos_albuns'   => $ultimos
        ];

        $this->render('dashboard/index', $viewData);
    }

    private function render($view, $data) {
        extract($data);
        // 1. Mudamos de ../../ para ../ para subir apenas para a pasta 'src'
        // 2. Mudamos 'views' para 'Views' (respeitando o Case Sensitive)
        require_once __DIR__ . "/../Views/{$view}.php";
    }
}