<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoundHaven - Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/loja.css">
</head>
<body>
    <?php include __DIR__ . '/../partials/header.php'; ?>

    <div class="page-wrapper">
        <div class="spacer-left"></div>

        <main class="content">
            <div class="store-grid">
                <?php if (empty($albuns)): ?>
                    <p style="grid-column: span 5; text-align: center; color: var(--text-secondary); padding: 50px;">
                        Nenhum álbum encontrado para os filtros aplicados.
                    </p>
                <?php else: ?>
                    <?php foreach ($albuns as $album): ?>
                        <article class="album-card album-card-modern" style="cursor:pointer;" data-album='<?= htmlspecialchars(json_encode($album), ENT_QUOTES, 'UTF-8') ?>'>
                            <img src="<?= htmlspecialchars($album['capa_url'] ?: 'assets/images/placeholder.jpg') ?>" alt="Capa">
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
            </div>
            <?php include __DIR__ . '/../partials/paginacao.php';?>
        </main>
        <?php include __DIR__ . '/../partials/sidebar_filtros.php';?>
    </div>

    <?php include __DIR__ . '/../partials/modal_detalhes.php'; ?>
    <?php include __DIR__ . '/../partials/modal_edicao.php'; ?>
    <?php include __DIR__ . '/../partials/modal_inclusao.php'; ?>

    <script src="assets/js/loja.js"></script>
</body>
</html>
