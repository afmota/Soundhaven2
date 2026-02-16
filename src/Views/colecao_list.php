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
            <span class="badge bg-primary fs-6"><?= $totalItens ?></span>
        </div>
        
        <form action="index.php" method="GET">
            <input type="hidden" name="action" value="colecao">
            <label class="small text-primary fw-bold uppercase mb-1">Buscar na Coleção</label>
            <input type="text" name="titulo" class="form-control form-control-sm shadow-none bg-dark text-white border-secondary" placeholder="Filtrar título...">
            
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
                    <div class="col-6 col-md-4 col-colecao-5">
                        <div class="card h-100 colecao-card">
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
                                        <?= $item['ano_lancamento'] ?>
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
                    <a class="page-link" href="<?= $urlBase ?>page=<?= $paginaAtual - 1 ?>">Anterior</a>
                </li>

                <?php for ($i = $pagInicio; $i <= $pagFim; $i++): ?>
                    <li class="page-item <?= ($i == $paginaAtual) ? 'active' : '' ?>">
                        <a class="page-link" href="<?= $urlBase ?>page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <li class="page-item <?= ($paginaAtual >= $totalPaginas) ? 'disabled' : '' ?>">
                    <a class="page-link" href="<?= $urlBase ?>page=<?= $paginaAtual + 1 ?>">Próxima</a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>