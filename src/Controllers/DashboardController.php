<?php
namespace App\Controllers;

use App\Repositories\DashboardRepository;

class DashboardController {
    private $repository;

    public function __construct($pdo) {
        $this->repository = new DashboardRepository($pdo);
    }

public function index() {
    $stats = $this->repository->buscarDadosGerais();
    $ultimos = $this->repository->buscarUltimasAquisicoes(5);
    $aniversariantes = $this->repository->buscarAniversariantesDoDia();
    $topArtistas = $this->repository->buscarTopArtistas(5);
    $top_gravadoras = $this->repository->buscarTopGravadoras(5);
    $top_produtores = $this->repository->buscarTopProdutores(5);
    $dados_formatos = $this->repository->buscarTotalPorFormato();
    
    // ADICIONADO: Busca a distribuição por anos
    $distribuicao_anos = $this->repository->buscarDistribuicaoPorAno();

    $viewData = [
        'total_albuns'     => $stats['total_albuns'] ?? 0,
        'total_lps'        => $stats['total_lps'] ?? 0,
        'total_cds'        => $stats['total_cds'] ?? 0,
        'total_artistas'   => $stats['total_artistas'] ?? 0,
        'total_gravadoras' => $stats['total_gravadoras'] ?? 0,
        'total_anos'       => $stats['total_anos'] ?? 0,
        'ultimos_albuns'   => $ultimos,
        'aniversariantes'  => $aniversariantes,
        'top_artistas'     => $topArtistas,
        'top_gravadoras'   => $top_gravadoras,
        'top_produtores'   => $top_produtores,
        'dados_formatos'   => $dados_formatos,
        'distribuicao_anos' => $distribuicao_anos // Envia para a View
    ];

    $this->render('dashboard/index', $viewData);
}

    private function render($view, $data) {
        extract($data);
        require_once __DIR__ . "/../Views/{$view}.php";
    }
}