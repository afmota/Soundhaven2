<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoundHaven - Dashboard</title>
   
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">

</head>
<body>

<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="dashboard-header-section container">
    <div class="dashboard-title">
        SoundHaven <span class="dashboard-album-count">| <?= $total_albuns ?> Álbuns</span>
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
            <div class="years-value">75</div> <div class="years-label">Anos de Música</div>
        </div>
    </div>
</div>

<div class="recent-albums-section container">
    <h2 class="recent-albums-title" style="color: #fff; margin-bottom: 20px;">Últimas Aquisições</h2>
    <div class="recent-albums-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
        <?php foreach ($ultimos_albuns as $album): ?>
            <div class="card album-card-modern" style="background: var(--cor-bg-card); padding: 15px; border-radius: 8px;">
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