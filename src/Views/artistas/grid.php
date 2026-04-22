<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoundHaven - Artistas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.0.0/css/flag-icons.min.css"/>
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/artistas.css"> 
</head>
<body>

<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="page-wrapper">
    <div class="spacer-left"></div>

    <div class="main-section">
        <main class="store-grid">
            <?php if (empty($artistas)): ?>
                <p style="grid-column: span 5; text-align: center; color: var(--text-secondary); padding: 50px;">
                    Nenhum artista com álbuns na coleção foi encontrado.
                </p>
            <?php else: ?>
                <?php foreach ($artistas as $artista): ?>
                    <article class="album-card album-card-modern js-open-artista-modal" 
                            data-artista='<?= htmlspecialchars(json_encode($artista), ENT_QUOTES, 'UTF-8') ?>'
                            style="cursor: pointer;">
                        
                        <img src="<?= htmlspecialchars($artista['imagem_url'] ?: 'assets/images/placeholder_artist.jpg') ?>" 
                            alt="<?= htmlspecialchars($artista['nome']) ?>">
                        
                        <div class="album-info">
                            <span class="album-title"><?= htmlspecialchars($artista['nome']) ?></span>
                            
                            <span class="artist-origin">
                                <?php if (!empty($artista['codigo_iso'])): ?>
                                    <span class="fi fi-<?= strtolower($artista['codigo_iso']) ?>"></span>
                                <?php else: ?>
                                    <i class="fa-solid fa-earth-americas"></i>
                                <?php endif; ?>
                                <?= htmlspecialchars($artista['pais_nome'] ?: 'Origem não informada') ?>
                            </span>

                            <span class="release-year">
                                <?= htmlspecialchars($artista['genero_nome'] ?: 'Gênero N/D') ?>
                            </span>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </main>

        <?php include __DIR__ . '/../partials/paginacao.php';?>
    </div>

    <?php include __DIR__ . '/../partials/modal_detalhes_artista.php'; ?>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
<script src="assets/js/artistas.js"></script>
</body>
</html>