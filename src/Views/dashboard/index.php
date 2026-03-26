<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoundHaven - Dashboard</title>
   
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">

</head>
<body>

<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="dashboard-header-section container">
    <div class="dashboard-title">
        SoundHaven <span class="dashboard-album-count">| Total de Álbuns: <?= $total_albuns ?> </span>
    </div>
</div>

<div class="metric-grid container">
    <div class="card metric-card">
        <div class="metric-card-content">
            <div>
                <div class="metric-value"><?= $total_albuns ?></div>
                <div class="metric-label">Total na Estante</div>
            </div>
            <div class="icon-container cor-1"><i class="fas fa-compact-disc"></i></div>
        </div>
    </div>

    <div class="card metric-card">
        <div class="metric-card-content">
            <div>
                <div class="metric-value"><?= $total_lps ?></div>
                <div class="metric-label">Vinil (LPs)</div>
            </div>
            <div class="icon-container cor-2"><i class="fas fa-record-vinyl"></i></div>
        </div>
    </div>

    <div class="card metric-card">
        <div class="metric-card-content">
            <div>
                <div class="metric-value"><?= $total_cds ?></div>
                <div class="metric-label">Compact Discs</div>
            </div>
            <div class="icon-container cor-3"><i class="fas fa-compact-disc"></i></div>
        </div>
    </div>

    <div class="card metric-card">
        <div class="metric-card-content">
            <div>
                <div class="metric-value"><?= $total_artistas ?></div>
                <div class="metric-label">Artistas Distintos</div>
            </div>
            <div class="icon-container cor-5"><i class="fas fa-users"></i></div>
        </div>
    </div>

    <div class="card metric-card">
        <div class="metric-card-content">
            <div>
                <div class="metric-value"><?= $total_gravadoras ?></div>
                <div class="metric-label">Gravadoras</div>
            </div>
            <div class="icon-container cor-5"><i class="fas fa-users"></i></div>
        </div>
    </div>
</div>

<div class="span-card-container container">
    <div class="card span-card">
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
    <div class="card anniversary-card">
        <div class="anniversary-title">
            <i class="fas fa-cake-candles"></i> Comemorando Hoje!
        </div>
        
        <?php foreach ($aniversariantes as $niver): ?>
            <a href="index.php?url=editar_album&midia_id=<?= $niver['id'] ?>" class="anniversary-album-item">
                <div class="album-cover-sm">
                    <?php if ($niver['capa_url']): ?>
                        <img src="<?= $niver['capa_url'] ?>" alt="Capa">
                    <?php else: ?>
                        <i class="fas fa-music"></i>
                    <?php endif; ?>
                </div>
                <div>
                    <h4><?= htmlspecialchars($niver['titulo']) ?></h4>
                    <p><?= htmlspecialchars($niver['artista_nome']) ?></p>
                    
                    <div class="anniversary-info-tag">
                        <i class="fas fa-star"></i>
                        <?php if ($niver['eh_aniversario_lancamento']): ?>
                            <?= $niver['anos_lancamento'] ?> anos de lançamento!
                        <?php else: ?>
                            <?= $niver['anos_aquisicao'] ?> anos na sua estante!
                        <?php endif; ?>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<div class="recent-albums-section container">
    <h2 class="recent-albums-title" style="color: #fff; margin-bottom: 20px;">Últimas Aquisições</h2>
    <div class="recent-albums-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
        <?php foreach ($ultimos_albuns as $album): ?>
            <div class="album-card-modern card" style="padding: 15px; border-radius: 8px;">
                <img src="<?= $album['capa_url'] ?>" style="width: 100%; border-radius: 4px; margin-bottom: 10px;">
                <h4 style="color: #fff; margin: 0;"><?= htmlspecialchars($album['titulo']) ?></h4>
                <p style="color: var(--cor-texto-secundario); font-size: 0.8em;"><?= htmlspecialchars($album['artista_nome']) ?> (<?= $album['ano_lancamento'] ?>)</p>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>