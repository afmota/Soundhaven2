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
        <div class="spacer-left">
            <div class="sidebar-metrics">
                <h3>Destaques</h3>

                <div class="card metric-card sidebar-card">
                    <a href="index.php?url=colecao&busca=<?= urlencode($albumMaisLongo['titulo']) ?>" 
                    style="text-decoration: none; color: inherit; display: block;">
                        <div class="metric-card-content" style="justify-content: flex-start; gap: 10px;">
                            <div class="icon-container cor-5">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <div class="metric-info">
                                <div class="metric-label">Maior Álbum</div>
                                <div class="metric-value">
                                    <?= $albumMaisLongo['titulo'] ?>
                                </div>
                                <div class="metric-time">
                                    <i class="far fa-clock"></i> <?= $albumMaisLongo['duracao'] ?>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="card metric-card sidebar-card">
                    <a href="index.php?url=colecao&busca=<?= urlencode($albumMaisCurto['titulo']) ?>" 
                    style="text-decoration: none; color: inherit; display: block;">
                        <div class="metric-card-content" style="justify-content: flex-start; gap: 10px;">
                            <div class="icon-container cor-2">
                                <i class="fas fa-compress-arrows-alt"></i>
                            </div>
                            <div class="metric-info">
                                <div class="metric-label">Menor Álbum</div>
                                <div class="metric-value">
                                    <?= $albumMaisCurto['titulo'] ?>
                                </div>
                                <div class="metric-time">
                                    <i class="far fa-clock"></i> <?= $albumMaisCurto['duracao'] ?>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <a href="index.php?url=colecao&busca=<?= urlencode($maiorMusica['album']) ?>"class="card metric-card sidebar-card" style="text-decoration: none; color: inherit; cursor: pointer;">
                    <div class="metric-card-content" style="justify-content: flex-start; gap: 10px;">
                        <div class="icon-container cor-3">
                            <i class="fas fa-arrows-alt-h"></i>
                        </div>
                        <div class="metric-info">
                            <div class="metric-label">Maior Música</div>
                            <div class="metric-value" title="<?= $maiorMusica['musica'] ?> "style="font-size: 0.9rem; margin-top: 5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 160px;">
                                <?= $maiorMusica['musica'] ?>
                            </div>
                            <div style="font-size: 0.75rem; color: #888; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 160px; margin-top: 5px;">
                                <?= $maiorMusica['album'] ?>
                            </div>
                            <div style="font-size: 0.8rem; color: #338d33; font-weight: bold; margin-top: 5px;">
                                <i class="far fa-clock"></i> <?= $maiorMusica['duracao'] ?>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="index.php?url=colecao&busca=<?= urlencode($menorMusica['album']) ?>" class="card metric-card sidebar-card" style="text-decoration: none; color: inherit; cursor: pointer;">
                    <div class="metric-card-content" style="justify-content: flex-start; gap: 10px;">
                        <div class="icon-container cor-4">
                            <i class="fas fa-compress"></i>
                        </div>
                        <div class="metric-info">
                            <div class="metric-label">Menor Música</div>
                            <div class="metric-value" title="<?= $menorMusica['musica'] ?>" style="font-size: 0.9rem; margin-top: 5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 160px;">
                                <?= $menorMusica['musica'] ?>
                            </div>
                            <div style="font-size: 0.75rem; color: #888; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 160px; margin-top: 5px;">
                                <?= $menorMusica['album'] ?>
                            </div>
                            <div style="font-size: 0.8rem; color: #338d33; font-weight: bold; margin-top: 5px;">
                                <i class="far fa-clock"></i> <?= $menorMusica['duracao'] ?>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <main class="content">
            <div class="metrics-row" style="display: flex; gap: 15px; width: 100%; margin-bottom: 25px; align-items: stretch;">
                <a href="index.php?url=colecao" class="card metric-card metric-row-card" style="text-decoration: none; color: inherit;">
                    <div class="metric-card-content">
                        <div>
                            <div class="metric-value"><?= $valorFormatado ?></div>
                            <div class="metric-label">Valor da Coleção</div>
                        </div>
                        <div class="icon-container cor-1"><i class="fas fa-hand-holding-usd"></i></div>
                    </div>
                </a>

                <div class="card metric-card metric-row-card">
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

                <div class="card metric-card metric-row-card">
                    <div class="metric-card-content">
                        <div>
                            <div class="metric-value"><?= $tempoTotal ?></div>
                            <div class="metric-label">Tempo de Audição</div>
                        </div>
                        <div class="icon-container cor-2"><i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>

                <div class="card metric-card metric-row-card">
                    <div class="metric-card-content">
                        <div>
                            <div class="metric-value"><?= $totalFaixas ?></div>
                            <div class="metric-label">Músicas (Faixas)</div>
                        </div>
                        <div class="icon-container cor-3"><i class="fas fa-list-ol"></i>
                        </div>
                    </div>
                </div>

                <div class="card metric-card metric-row-card">
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

        <aside class="spacer-right">
            <div class="card metric-card metric-row-card" onclick="abrirModalDecadas()" style="cursor: pointer; margin-bottom: 15px; border-right: 4px solid #3c3cff;">
                <div class="metric-card-content" style="justify-content: flex-start; gap: 10px;">
                    <div class="icon-container" style="background-color: #3c3cff22; color: #3c3cff;">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <div class="metric-info">
                        <div class="metric-label">Distribuição</div>
                        <div class="metric-value" style="font-size: 1rem;">Por Década</div>
                    </div>
                </div>
            </div>

            <div class="card metric-card metric-row-card" onclick="abrirModalAnos()" style="cursor: pointer; margin-bottom: 15px; border-right: 4px solid #338d33;">
                <div class="metric-card-content" style="justify-content: flex-start; gap: 10px;">
                    <div class="icon-container" style="background-color: #338d3322; color: #338d33;">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="metric-info">
                        <div class="metric-label">Aquisições</div>
                        <div class="metric-value" style="font-size: 1rem;">Por Ano</div>
                    </div>
                </div>
            </div>

            <div class="filter-toggle-container" style="margin-bottom: 15px;">
                <button type="button" id="btnToggleFiltros" class="btn-filter-trigger" style="background: #3c3cff; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; font-weight: bold;">
                    <i class="fas fa-sliders-h"></i> <span id="txtToggleFiltros">Mostrar Filtros</span>
                </button>
            </div>

            <div id="barraFiltrosAvancados" class="filtros-avancados-panel" style="display: none; background: #222; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <form action="index.php" method="GET" class="filtros-form" style="display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap;">
                    <input type="hidden" name="url" value="colecao">

                    <div class="form-group" style="flex: 1; min-width: 200px;">
                        <label style="display: block; color: #aaa; font-size: 0.85rem; margin-bottom: 5px;">Nome do Álbum</label>
                        <input type="text" name="busca" value="<?= htmlspecialchars($_GET['busca'] ?? '') ?>" placeholder="Ex: Dark Side of the Moon..." style="width: 100%; padding: 8px; background: #333; border: 1px solid #444; color: white; border-radius: 4px;">
                    </div>

                    <div class="form-group" style="flex: 1; min-width: 200px;">
                        <label style="display: block; color: #aaa; font-size: 0.85rem; margin-bottom: 5px;">Produtor</label>
                        <input type="text" name="produtor" value="<?= htmlspecialchars($_GET['produtor'] ?? '') ?>" placeholder="Ex: George Martin..." style="width: 100%; padding: 8px; background: #333; border: 1px solid #444; color: white; border-radius: 4px;">
                    </div>

                    <div class="form-actions" style="display: flex; gap: 10px;">
                        <button type="submit" style="background: #338d33; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer; font-weight: bold;">
                            Filtrar
                        </button>
                        <?php if (!empty($_GET['busca']) || !empty($_GET['produtor'])): ?>
                            <a href="index.php?url=colecao" style="background: #ff3838; color: white; text-decoration: none; padding: 8px 15px; border-radius: 4px; font-weight: bold; font-size: 0.85rem;">
                                Limpar
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>


        </aside>
    </div>
    <?php include __DIR__ . '/../partials/paginacao.php';?>


    <?php include __DIR__ . '/../partials/modal_detalhes_colecao.php'; ?>
    <?php include __DIR__ . '/../partials/modal_grafico_decada.php'; ?>
    <?php include __DIR__ . '/../partials/modal_grafico_anos.php'; ?>
    <?php include __DIR__ . '/../partials/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const dadosDecadas = <?= $jsonDecadas ?>;
        const dadosAquisicoes = <?= $jsonAquisicoes ?>;
    </script>

    <script src="assets/js/functions.js"></script>
    <script src="assets/js/colecao.js"></script>
</body>
</html>