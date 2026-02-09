<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soundhaven Shop</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <aside class="col-md-3 col-lg-2 sidebar">
            <h3 class="text-success fw-bold mb-4"><i class="bi bi-vinyl-fill"></i> Shop</h3>
            
            <form method="GET" action="">
                <div class="mb-3">
                    <label class="form-label small text-uppercase opacity-50">Busca rápida</label>
                    <input type="text" name="titulo" class="form-control form-control-sm bg-dark text-white border-0 shadow-none" 
                           placeholder="Título do álbum..." value="<?= htmlspecialchars($filtros['titulo'] ?? '') ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label small text-uppercase opacity-50">Artista</label>
                    <select name="artista" class="form-select form-select-sm bg-dark text-white border-0 shadow-none">
                        <option value="">Todos os Artistas</option>
                        <?php foreach ($listaArtistas as $artista): ?>
                            <option value="<?= $artista['id'] ?>" <?= ($filtros['artista'] ?? '') == $artista['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($artista['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-success btn-sm w-100 mt-4 fw-bold">APLICAR FILTROS</button>
                <a href="?" class="btn btn-link btn-sm w-100 mt-2 text-decoration-none text-muted">Limpar</a>
            </form>
        </aside>

        <main class="col-md-9 col-lg-10 p-4">
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-4">
                <?php foreach ($albuns as $album): ?>
                    <div class="col">
                        <?php 
                            $jsonDados = json_encode([
                                'titulo'   => $album->getTitulo(),
                                'artista'  => $album->getArtistaNome(),
                                'capa'     => $album->getCapaUrl(),
                                'ano'      => date('Y', strtotime($album->getDataLancamento())),
                                'tipo'     => $album->getTipoId(),
                                'situacao' => $album->getSituacao(),
                                'inclusao' => date('d/m/Y', strtotime($album->getDataLancamento()))
                            ]);
                        ?>
                        <article class="album-card h-100" onclick='abrirModal(<?= htmlspecialchars($jsonDados, ENT_QUOTES, "UTF-8") ?>)'>
                            <img src="<?= $album->getCapaUrl() ?>" class="card-img-top" alt="<?= htmlspecialchars($album->getTitulo()) ?>">
                            <div class="card-body py-2">
                                <h6 class="text-truncate mb-0"><?= htmlspecialchars($album->getTitulo()) ?></h6>
                                <p class="small text-success mb-0"><?= htmlspecialchars($album->getArtistaNome()) ?></p>
                            </div>
                        </article>
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
        </main>
    </div>
</div>

<?php include __DIR__ . '/partials/modal_detalhes.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="/js/album-actions.js"></script>

</body>
</html>