<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Config/Database.php';

use App\Config\Database;
use App\Repositories\DashboardRepository;

session_start();

// Simula usuário logado
$_SESSION['usuario_id'] = 1;

try {
    $pdo = Database::getConnection();
    $repo = new DashboardRepository($pdo);
    $dados = $repo->buscarDadosGerais();
    echo "OK:\n";
    print_r($dados);
} catch (\Throwable $e) {
    echo "ERRO: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}

