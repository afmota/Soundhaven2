<?php
$path = __DIR__ . '/../src/Repositories/DashboardRepository.php';
$src = file_get_contents($path);

if ($src === false) {
    echo "Não foi possível ler o arquivo\n";
    exit(1);
}

if (!preg_match('/public function buscarDadosGerais\(\) \{([\s\S]*?)\n\s*\$/', $src, $m)) {
    // fallback: capture $sql = "..." directly
}

// Captura o conteúdo do $sql = "..." dentro da função
if (preg_match('/\$sql\s*=\s*"([\s\S]*?)"\s*;/m', $src, $match)) {
    $sql = $match[1];
    // Normaliza espaços
    $sqlClean = trim($sql);
    $placeholders = substr_count($sqlClean, '?');
    echo "SQL extraída (trecho inicial):\n" . substr($sqlClean, 0, 200) . "\n\n";
    echo "Contagem de placeholders '?' = {$placeholders}\n";
    // Esperamos 6 placeholders
    if ($placeholders === 6) {
        echo "OK: Número de placeholders corresponde ao esperado (6).\n";
        exit(0);
    } else {
        echo "AVISO: Número de placeholders não é 6. Verifique manualmente.\n";
        exit(2);
    }
} else {
    echo "Não consegui extrair a SQL de buscarDadosGerais().\n";
    exit(3);
}
