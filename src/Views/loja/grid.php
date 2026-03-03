<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoundHaven - Dark Store</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="store-container">
    <header>
        <h1 style="text-align: center; color: var(--accent-color);">SOUNDHAVEN</h1>
    </header>

    <main class="store-grid">
        <?php if (empty($albuns)): ?>
            <p style="grid-column: span 5; text-align: center;">Nenhum álbum encontrado.</p>
        <?php else: ?>
            <?php foreach ($albuns as $album): ?>
                <article class="album-card">
                    <img src="<?= htmlspecialchars($album['capa_url'] ?: 'assets/images/placeholder.jpg') ?>" 
                         alt="Capa">
                    <div class="album-info">
                        <span class="album-title"><?= htmlspecialchars($album['titulo']) ?></span>
                        <span class="artist-name"><?= htmlspecialchars($album['artista_nome']) ?></span>
                        <span class="release-year">
                            <?= $album['data_lancamento'] ? date('Y', strtotime($album['data_lancamento'])) : 'N/D' ?>
                        </span>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>

    <nav class="pagination">
        <?php if ($paginaAtual > 1): ?>
            <a href="?url=loja&page=1">Primeira</a>
        <?php endif; ?>

        <?php for ($i = $inicioPagina; $i <= $fimPagina; $i++): ?>
            <a href="?url=loja&page=<?= $i ?>" class="<?= $i == $paginaAtual ? 'active' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>

        <?php if ($paginaAtual < $totalPaginas): ?>
            <a href="?url=loja&page=<?= $totalPaginas ?>">Última</a>
        <?php endif; ?>
    </nav>
</div>

</body>
</html>