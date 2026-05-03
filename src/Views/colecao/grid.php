<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoundHaven - Coleção</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/colecao.css">
</head>
<body>

    <?php include __DIR__ . '/../partials/header.php'; ?>

    <div class="page-wrapper">
        <div class="left-spacer"></div>

        <main class="content">
            <div class="metrics-row" style="display: flex; gap: 15px; width: 100%; margin-bottom: 25px; align-items: stretch;">
                <div class="card metric-card no-click">
                    <div class="metric-card-content">
                        <div>
                            <div class="metric-value"><?= $valorFormatado ?></div>
                            <div class="metric-label">Valor da Coleção</div>
                        </div>
                        <div class="icon-container cor-1"><i class="fas fa-hand-holding-usd"></i></div>
                    </div>
                </div>

                <div class="card metric-card">
                    <a href="index.php?url=colecao&busca=<?= urlencode($maisCaro['titulo']) ?>" style="text-decoration: none; color: inherit; display: block; width: 100%;">
                        <div class="metric-card-content">
                            <div>
                                <div class="metric-value" style="font-size: 1.1rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 150px;">
                                    <?= $maisCaro['titulo'] ?>
                                </div>
                                <div class="metric-label">+ Valioso (<?= $maisCaro['preco'] ?>)</div>
                            </div>
                            <div class="icon-container cor-6" style="background-color: #e91e6322; color: #e91e63;">
                                <i class="fas fa-crown"></i>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="card metric-card no-click">
                    <div class="metric-card-content">
                        <div>
                            <div class="metric-value"><?= $tempoTotal ?></div>
                            <div class="metric-label">Tempo de Audição</div>
                        </div>
                        <div class="icon-container cor-2"><i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>

                <div class="card metric-card no-click">
                    <div class="metric-card-content">
                        <div>
                            <div class="metric-value"><?= $totalFaixas ?></div>
                            <div class="metric-label">Músicas (Faixas)</div>
                        </div>
                        <div class="icon-container cor-3"><i class="fas fa-list-ol"></i>
                        </div>
                    </div>
                </div>

                <div class="card metric-card no-click">
                    <div class="metric-card-content">
                        <div>
                            <div class="metric-value"><?= $tempoMedio ?></div>
                            <div class="metric-label">Duração Média</div>
                        </div>
                        <div class="icon-container cor-4" style="background-color: #3c3cff22; color: #3c3cff;">
                            <i class="fas fa-stopwatch"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="store-grid">
                <?php if (empty($albuns)): ?>
                    <p style="grid-column: span 5; text-align: center; color: var(--text-secondary); padding: 50px;">
                        Sua coleção ainda está vazia. Adquira álbuns na loja para vê-los aqui!
                    </p>
                <?php else: ?>
                    <?php foreach ($albuns as $album): ?>
                        <article class="album-card album-card-modern" style="cursor:pointer;" data-album='<?= htmlspecialchars(json_encode($album), ENT_QUOTES, 'UTF-8') ?>'>
                            
                            <span class="format-tag" style="background-color: <?= $album['formato_cor'] ?> !important;">
                                <?= htmlspecialchars($album['formato_nome']) ?>
                            </span>

                            <img loading="lazy" src="<?= htmlspecialchars($album['capa_url'] ?: 'assets/images/placeholder.jpg') ?>" alt="Capa">

                            <button class="btn-ouvir-tag" data-midia-id="<?= $album['midia_id'] ?>" 
                                    title="Marcar como ouvido hoje">
                                <i class="fas fa-headphones"></i>
                            </button>
                            
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
            </div>
        </main>
        <?php include __DIR__ . '/../partials/paginacao.php';?>
    </div>

    <?php include __DIR__ . '/../partials/modal_detalhes_colecao.php'; ?>
    <?php include __DIR__ . '/../partials/footer.php'; ?>
    
    <script src="assets/js/functions.js"></script>
    <script src="assets/js/colecao.js"></script>
</body>
</html>