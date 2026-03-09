<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoundHaven - Coleção</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/loja.css">
</head>
<body class="colecao-module">

<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="page-wrapper">
    <div class="spacer-left"></div>

    <div class="main-section">
        <main class="store-grid">
            <?php if (empty($albuns)): ?>
                <p style="grid-column: span 5; text-align: center; color: var(--text-secondary); padding: 50px;">
                    Sua coleção ainda está vazia. Adquira álbuns na loja para vê-los aqui!
                </p>
            <?php else: ?>
                <?php foreach ($albuns as $album): ?>
                    <article class="album-card" 
                             data-album='<?= htmlspecialchars(json_encode($album), ENT_QUOTES, 'UTF-8') ?>'>
                        
                        <span class="format-tag" style="background-color: <?= $album['formato_cor'] ?> !important;">
                            <?= htmlspecialchars($album['formato_nome']) ?>
                        </span>

                        <img src="<?= htmlspecialchars($album['capa_url'] ?: 'assets/images/placeholder.jpg') ?>" alt="Capa">
                        
                        <div class="album-info">
                            <span class="album-title"><?= htmlspecialchars($album['titulo']) ?></span>
                            <span class="artist-name"><?= htmlspecialchars($album['artista_nome']) ?></span>

                            <span class="acquisition-year">
                                <?= $album['data_aquisicao'] ? date('Y', strtotime($album['data_aquisicao'])) : 'N/D' ?>
                            </span>

                            <span class="label-tag">
                                <?= htmlspecialchars($album['gravadora_nome'] ?: 'N/D') ?>
                            </span>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </main>

        <?php include __DIR__ . '/../partials/paginacao.php';?>
    </div>

    <?php include __DIR__ . '/../partials/sidebar_filtros.php'; ?>
</div>

<script src="assets/js/colecao.js"></script>
</body>
</html>