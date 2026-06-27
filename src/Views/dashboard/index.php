<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoundHaven - Dashboard</title>
   
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link rel="icon" type="image/x-icon" href="/public/assets/images/SoundHaven.ico">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
<body>
    <?php require_once __DIR__ . '/../partials/header.php'; ?>

    <div class="page-wrapper">
        <main class="content">
            <div class="metric-grid container">
                <div class="card metric-card">
                    <a href="index.php?url=colecao" style="text-decoration: none; color: inherit; display: block; width: 100%;">
                        <div class="metric-card-content">
                            <div>
                                <div class="metric-value"><?= $total_albuns ?></div>
                                <div class="metric-label">Total de Álbuns</div>
                            </div>
                            <div class="icon-container cor-1"><i class="fas fa-compact-disc"></i></div>
                        </div>
                    </a>
                </div>
                <div class="card metric-card">
                    <a href="index.php?url=colecao&formato_id=1" style="text-decoration: none; color: inherit; display: block; width: 100%;">
                        <div class="metric-card-content">
                            <div>
                                <div class="metric-value"><?= $total_lps ?></div>
                                <div class="metric-label">Vinil (LPs)</div>
                            </div>
                            <div class="icon-container cor-2"><i class="fas fa-record-vinyl"></i></div>
                        </div>
                    </a>
                </div>
                <div class="card metric-card">
                    <a href="index.php?url=colecao&formato_id=2" style="text-decoration: none; color: inherit; display: block; width: 100%;">
                        <div class="metric-card-content">
                            <div>
                                <div class="metric-value"><?= $total_cds ?></div>
                                <div class="metric-label">Compact Discs</div>
                            </div>
                            <div class="icon-container cor-3"><i class="fas fa-compact-disc"></i></div>
                        </div>
                    </a>
                </div>
                <div class="card metric-card" id="cardTotalArtistas" style="cursor: pointer;">
                    <div class="metric-card-content">
                        <div>
                            <div class="metric-value"><?= $total_artistas ?></div>
                            <div class="metric-label">Artistas Distintos</div>
                        </div>
                        <div class="icon-container cor-4"><i class="fas fa-users"></i></div>
                    </div>
                </div>
                <div class="card metric-card" id="cardTotalGravadoras" style="cursor: pointer;">
                    <div class="metric-card-content">
                        <div>
                            <div class="metric-value"><?= $total_gravadoras ?></div>
                            <div class="metric-label">Gravadoras</div>
                        </div>
                        <div class="icon-container cor-5"><i class="fas fa-users"></i></div>
                    </div>
                </div>
            </div>
            <div class="charts-section container">
                <div class="card chart-card">
                    <h3 class="chart-title"><i class="fas fa-trophy"></i> Top 5 Artistas</h3>
                    <div id="containerChartTopArtistas"
                        class="chart-container"
                        style="position: relative; height:180px;"
                        data-artistas='<?= json_encode($top_artistas) ?>'>
                        <canvas id="chartTopArtistas"></canvas>
                    </div>
                </div>
                <div class="card chart-card">
                    <h3 class="chart-title"><i class="fas fa-compact-disc"></i> Top 5 Gravadoras</h3>
                    <div id="containerChartTopGravadoras"
                        class="chart-container"
                        style="position: relative; height:180px;"
                        data-gravadoras='<?= json_encode($top_gravadoras) ?>'>
                        <canvas id="chartTopGravadoras"></canvas>
                    </div>
                </div>
                <div class="card chart-card">
                    <h3 class="chart-title"><i class="fas fa-compact-disc"></i> Top 5 Gêneros</h3>
                    <div id="containerChartTopGeneros"
                        class="chart-container"
                        style="position: relative; height:180px;"
                        data-generos='<?= json_encode($top_generos) ?>'>
                        <canvas id="chartTopGeneros"></canvas>
                    </div>
                </div>
                <div class="card chart-card">
                    <h3 class="chart-title"><i class="fas fa-compact-disc"></i> Formatos</h3>
                    <div id="containerChartFormatos"
                        class="chart-container"
                        style="position: relative; height:180px;"
                        data-formatos='<?= htmlspecialchars(json_encode($dados_formatos), ENT_QUOTES, 'UTF-8') ?>'>
            
                        <canvas id="chartFormatos"></canvas>
            
                        <div class="chart-center-text">
                            <span style="display: block; font-size: 1.5rem; font-weight: bold; color: #fff;"><?= $total_albuns ?></span>
                            <span style="font-size: 0.7rem; color: #aaa; text-transform: uppercase;">Total</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="span-card-container container">
                <div class="card span-card" id="btnAbrirModalAnos"
                    style="cursor: pointer;"
                    data-anos='<?= json_encode($distribuicao_anos) ?>'>
                    <div class="span-details">
                        <i class="fas fa-history"></i>
                        <div>
                            <div class="span-title">Abrangência da Coleção</div>
                            <div class="span-years-range">Do primeiro lançamento ao mais recente</div>
                        </div>
                    </div>
                    <div class="span-value-area">
                        <div class="years-value"><?= $total_anos ?></div>
                        <div class="years-label">Anos de Música</div>
                    </div>
                </div>
            </div>
            <?php if (!empty($aniversariantes)): ?>
            <div class="anniversary-section container">
                <h2 class="recent-albums-title">
                    <i class="fas fa-cake-candles" style="margin-right: 8px;"></i> Comemorando Hoje
                </h2>
                <div class="recent-albums-slider anniversary-slider" data-slider="anniversary">
                    <button type="button" class="recent-albums-nav prev" aria-label="Anterior">&#8249;</button>
                    <div class="recent-albums-track" id="anniversarySlider">
                        <?php foreach ($aniversariantes as $niver): ?>
                        <div class="card album-card-modern slider-card abrir-modal-detalhes"
                            style="cursor: pointer;"
                            data-album='<?= htmlspecialchars(json_encode($niver), ENT_QUOTES, 'UTF-8') ?>'>
                            <img src="<?= htmlspecialchars($niver['capa_url'] ?: 'assets/images/placeholder.jpg') ?>" alt="Capa">
                            <div class="album-card-info">
                                <h4><?= htmlspecialchars($niver['titulo']) ?></h4>
                                <p><?= htmlspecialchars($niver['artista_nome']) ?></p>
                                <p style="font-size: 0.75rem; color: #facc15; margin-top: 6px;">
                                    <?php if ($niver['eh_aniversario_lancamento']): ?>
                                        <?= $niver['anos_lancamento'] ?> anos de lançamento
                                    <?php else: ?>
                                        <?= $niver['anos_aquisicao'] ?> anos na sua estante
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="recent-albums-nav next" aria-label="Próximo">&#8250;</button>
                </div>
            </div>
            <?php endif; ?>
            <div class="recent-albums-section container">
                <h2 class="recent-albums-title">Últimas Aquisições</h2>
                <div class="recent-albums-slider recent-slider" data-slider="recent">
                    <button type="button" class="recent-albums-nav prev" aria-label="Anterior">&#8249;</button>
                    <div class="recent-albums-track" id="recentAlbumsSlider">
                        <?php foreach ($ultimos_albuns as $album): ?>
                        <div class="card album-card-modern slider-card abrir-modal-detalhes" 
                            style="cursor: pointer;"
                            data-album='<?= htmlspecialchars(json_encode($album), ENT_QUOTES, 'UTF-8') ?>'>
                            <img src="<?= htmlspecialchars($album['capa_url'] ?: 'assets/images/placeholder.jpg') ?>" alt="Capa">
                            <div class="album-card-info">
                                <h4><?= htmlspecialchars($album['titulo']) ?></h4>
                                <p><?= htmlspecialchars($album['artista_nome']) ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="recent-albums-nav next" aria-label="Próximo">&#8250;</button>
                </div>
            </div>
        </main>
        <?php require_once __DIR__ . '/../partials/modal_detalhes_colecao.php'; ?>
        <?php require_once __DIR__ . '/../partials/modal_grafico_artistas.php'; ?>
        <?php require_once __DIR__ . '/../partials/modal_grafico_gravadoras.php'; ?>
        <?php include __DIR__ . '/../partials/modal_sugestao.php'; ?>
        <?php include __DIR__ . '/../partials/modal_linha_tempo.php'; ?>
        <?php require_once __DIR__ . '/../partials/footer.php'; ?>
    </div>

    <script src="assets/js/functions.js"></script>
    <script src="assets/js/colecao.js"></script>
    <script src="assets/js/dashboard.js"></script>
</body>
</html>