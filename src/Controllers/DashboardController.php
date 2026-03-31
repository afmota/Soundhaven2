<?php
namespace App\Controllers;

use App\Repositories\DashboardRepository;

class DashboardController {
    private $repository;

    public function __construct($pdo) {
        $this->repository = new DashboardRepository($pdo);
    }

    public function index() {
        // Busca as métricas e as últimas aquisições
        $stats = $this->repository->buscarDadosGerais(); 
        $ultimos = $this->repository->buscarUltimasAquisicoes(5);
        
        // Busca os álbuns que fazem aniversário hoje
        $aniversariantes = $this->repository->buscarAniversariantesDoDia();

        // ADICIONADO: Busca o Top 5 Artistas para o gráfico
        $topArtistas = $this->repository->buscarTopArtistas(5);
    
        $viewData = [
            'total_albuns'     => $stats['total_albuns'] ?? 0,
            'total_lps'        => $stats['total_lps'] ?? 0,
            'total_cds'        => $stats['total_cds'] ?? 0,
            'total_artistas'   => $stats['total_artistas'] ?? 0,
            'total_gravadoras' => $stats['total_gravadoras'] ?? 0,
            'total_anos'       => $stats['total_anos'] ?? 0,
            'ultimos_albuns'   => $ultimos,
            'aniversariantes'  => $aniversariantes,
            'top_artistas'     => $topArtistas // <-- ESSA LINHA RECHEIA O CARD
        ];
    
        $this->render('dashboard/index', $viewData);
    }

    private function render($view, $data) {
        extract($data);
        require_once __DIR__ . "/../Views/{$view}.php";
    }
}