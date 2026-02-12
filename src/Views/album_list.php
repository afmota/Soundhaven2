<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Soundhaven - Vitrine Dark</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <aside class="filter-panel">
        <h6 class="text-white mb-3 text-center border-bottom pb-2" style="border-color: #1db954 !important;">LOJA FILTROS</h6>
        <form action="index.php" method="GET">
            <label>Título</label>
            <input type="text" name="titulo" class="form-control form-control-sm shadow-none" value="<?= htmlspecialchars($filtros['titulo'] ?? '') ?>">

            <label>Artista</label>
            <select name="artista" class="form-select form-select-sm shadow-none">
                <option value="">Todos os Artistas</option>
                <?php foreach ($listaArtistas as $artista): ?>
                    <option value="<?= $artista['id'] ?>" <?= ($filtros['artista'] ?? '') == $artista['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($artista['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>    

            <label>Tipo do Álbum</label>
            <select name="tipo" class="form-select form-select-sm shadow-none">
                <option value="">Todos</option>
                <option value="1" <?= ($filtros['tipo'] ?? '') == '1' ? 'selected' : '' ?>>Estúdio</option>
                <option value="2" <?= ($filtros['tipo'] ?? '') == '2' ? 'selected' : '' ?>>EP</option>
                <option value="3" <?= ($filtros['tipo'] ?? '') == '3' ? 'selected' : '' ?>>Ao Vivo</option>
                <option value="4" <?= ($filtros['tipo'] ?? '') == '4' ? 'selected' : '' ?>>Compilação</option>
                <option value="5" <?= ($filtros['tipo'] ?? '') == '5' ? 'selected' : '' ?>>Trilha Sonora</option>
            </select>

            <label>Situação</label>
            <select name="situacao" class="form-select form-select-sm shadow-none">
                <option value="">Padrão (Vitrine)</option>
                <option value="1" <?= ($filtros['situacao'] ?? '') == '1' ? 'selected' : '' ?>>Disponível</option>
                <option value="2" <?= ($filtros['situacao'] ?? '') == '2' ? 'selected' : '' ?>>Selecionado</option>
                <option value="3" <?= ($filtros['situacao'] ?? '') == '3' ? 'selected' : '' ?>>Baixado</option>
                <option value="4" <?= ($filtros['situacao'] ?? '') == '4' ? 'selected' : '' ?>>Adquirido</option>
                <option value="5" <?= ($filtros['situacao'] ?? '') == '5' ? 'selected' : '' ?>>Descartado</option>
            </select>

            <button type="submit" class="btn btn-success btn-sm w-100 mt-3 fw-bold">FILTRAR AGORA</button>
            <a href="index.php" class="btn btn-outline-danger btn-sm w-100 mt-2">LIMPAR</a>
        </form>
    </aside>

    <div class="container-fluid py-5 vitrine-container">
        <header class="text-center mb-5">
            <h1 class="display-5 fw-bold text-white">Soundhaven Shop</h1>
            <p class="text-muted">Explore sua loja de álbuns</p>
        </header>
        
        <div class="row g-4 justify-content-center">
            <?php foreach ($albuns as $album): ?>
                <?php 
                    $dadosJson = json_encode([
                        'titulo'   => $album->getTitulo(),
                        'artista'  => $album->getArtistaNome(),
                        'capa'     => $album->getCapaUrl(),
                        'ano'      => $album->getAnoLancamento(),
                        'tipo'     => $album->getTipo(),
                        'situacao' => $album->getSituacao(),
                        'inclusao' => date('d/m/Y', strtotime($album->getDataCriacao()))
                    ]);
                ?>
                <div class="col-6 col-md-4 col-custom-5">
                    <div class="card h-100 shadow-sm album-card" onclick='abrirModal(<?= htmlspecialchars($dadosJson, ENT_QUOTES, "UTF-8") ?>)'>
                        <div class="album-img-container">
                            <img src="<?= $album->getCapaUrl() ?>" 
                                 alt="<?= htmlspecialchars($album->getTitulo()) ?>"
                                 loading="lazy">
                        </div>
                        <div class="card-body p-3 text-center">
                            <h6 class="card-title text-truncate mb-1" title="<?= htmlspecialchars($album->getTitulo()) ?>">
                                <?= htmlspecialchars($album->getTitulo()) ?>
                            </h6>
                            <p class="text-primary small mb-1">
                                <strong><?= htmlspecialchars($album->getArtistaNome()) ?></strong>
                            </p>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="badge bg-dark text-muted border border-secondary">
                                    <?= $album->getAnoLancamento() ?>
                                </span>
                                <small class="text-muted small">#<?= $album->getArtistaId() ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <nav class="mt-5">
            <ul class="pagination pagination-sm justify-content-center">
                <?php for ($i = $pagInicio; $i <= $pagFim; $i++): ?>
                    <li class="page-item <?= ($i == $paginaAtual) ? 'active' : '' ?>">
                        <a class="page-link" href="<?= $urlBase ?>page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>

    <?php include __DIR__ . '/partials/modal_detalhes.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/album-actions.js"></script>
</body>
</html>