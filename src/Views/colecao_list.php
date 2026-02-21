<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Soundhaven - Minha Coleção</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <aside class="colecao-sidebar">
        <h6 class="text-white mb-3 text-center border-bottom pb-2" style="border-color: #0d6efd !important;">ESTANTE</h6>
        <div class="text-center mb-4">
            <small class="text-muted d-block">TOTAL NA ESTANTE</small>
            <span class="badge bg-primary fs-6"><?= $totalAlbuns ?></span>
        </div>
        
        <form action="index.php" method="GET">
            <input type="hidden" name="action" value="colecao">
            <label class="small text-primary fw-bold uppercase mb-1">Buscar na Coleção</label>
            <input type="text" name="titulo" class="form-control form-control-sm shadow-none bg-dark text-white border-secondary" 
                   placeholder="Filtrar título..." value="<?= htmlspecialchars($_GET['titulo'] ?? '') ?>">
            
            <button type="submit" class="btn btn-primary btn-sm w-100 mt-3 fw-bold">ATUALIZAR VISTA</button>
            <a href="index.php?action=colecao" class="btn btn-outline-secondary btn-sm w-100 mt-2">LIMPAR</a>
        </form>
    </aside>

    <div class="container-fluid py-5 colecao-vitrine">
        <header class="text-center mb-5">
            <h1 class="display-6 fw-bold text-white">Minha Coleção Física</h1>
            <p class="text-muted small uppercase">Página <?= $paginaAtual ?> de <?= $totalPaginas ?></p>
        </header>
        
        <div class="row g-4 justify-content-center">
            <?php if (empty($albuns)): ?>
                <div class="col-12 text-center py-5">
                    <p class="text-muted">Sua estante está vazia ou o filtro não retornou resultados.</p>
                </div>
            <?php else: ?>
                <?php foreach ($albuns as $item): ?>
                    <?php 
                        $dadosJson = json_encode([
                            'id' => $item['id'],
                            'titulo' => $item['titulo'],
                            'artista_id' => $item['artista_id'],
                            'artista_nome' => $item['artista_nome'],
                            'capa' => $item['capa_url'],
                            'data_lancamento' => $item['data_lancamento'] ?? '',
                            'tipo_id' => $item['tipo_id'],
                            'gravadora_id' => $item['gravadora_id'],
                            'gravadora_nome' => $item['gravadora_nome'],
                            'formato_id' => $item['formato_id'],
                            'formato_nome' => $item['formato_nome'],
                            'numero_catalogo' => $item['numero_catalogo'] ?? '',
                            'aquisicao' => $item['data_aquisicao'] ?? '',
                            'generos' => $item['generos'] ?? '',
                            'estilos' => $item['estilos'] ?? '',
                            'produtores' => $item['produtores'] ?? '',
                            'faixas' => $item['faixas'] ?? '',
                            'observacoes' => $item['observacoes'] ?? ''
                        ]);
                    ?>
                    <div class="col-6 col-md-4 col-colecao-5">
                        <div class="card h-100 colecao-card" onclick='abrirModalColecao(<?= htmlspecialchars($dadosJson, ENT_QUOTES, "UTF-8") ?>)'>
                            <div class="colecao-img-container">
                                <img src="<?= htmlspecialchars($item['capa_url']) ?>" 
                                     alt="<?= htmlspecialchars($item['titulo']) ?>"
                                     loading="lazy">
                            </div>
                            <div class="card-body p-3 text-center">
                                <h6 class="card-title text-truncate mb-1 text-white" title="<?= htmlspecialchars($item['titulo']) ?>">
                                    <?= htmlspecialchars($item['titulo']) ?>
                                </h6>
                                <p class="text-colecao-primary small mb-1">
                                    <strong><?= htmlspecialchars($item['artista_nome']) ?></strong>
                                </p>
                                <div class="d-flex justify-content-center mt-2">
                                    <span class="badge bg-dark text-muted border border-secondary" style="font-size: 0.7rem;">
                                        <?= $item['ano_lancamento'] ?? '----' ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php if ($totalPaginas > 1): ?>
        <nav class="mt-5">
            <ul class="pagination pagination-sm justify-content-center">
                <li class="page-item <?= ($paginaAtual <= 1) ? 'disabled' : '' ?>">
                    <a class="page-link" href="<?= $urlBase ?>page=<?= max(1, $paginaAtual - 1) ?>">Anterior</a>
                </li>
                <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                    <?php if ($i == 1 || $i == $totalPaginas || ($i >= $paginaAtual - 2 && $i <= $paginaAtual + 2)): ?>
                        <li class="page-item <?= ($i == $paginaAtual) ? 'active' : '' ?>">
                            <a class="page-link" href="<?= $urlBase ?>page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php elseif ($i == $paginaAtual - 3 || $i == $paginaAtual + 3): ?>
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                    <?php endif; ?>
                <?php endfor; ?>
                <li class="page-item <?= ($paginaAtual >= $totalPaginas) ? 'disabled' : '' ?>">
                    <a class="page-link" href="<?= $urlBase ?>page=<?= min($totalPaginas, $paginaAtual + 1) ?>">Próxima</a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>
    </div>

    <?php include __DIR__ . '/partials/modal_colecao.php'; ?>
    <?php include __DIR__ . '/partials/modal_edicao_colecao.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/colecao-actions.js"></script>
</body>
</html>