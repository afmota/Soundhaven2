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

    <main class="content">
        <div class="page-actions" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; gap: 10px; flex-wrap: wrap;">
            <h1 style="color: var(--accent-color);">Artistas</h1>
            <a href="?url=novo_artista" class="btn btn-primary" style="text-decoration: none;">+ Novo Artista</a>
        </div>

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
    </main>

    <aside class="spacer-right">
        <div class="card metric-card metric-row-card filters-card">
            <h3 style="margin-bottom: 18px; color: var(--text-primary);">Filtros</h3>
            <form action="?" method="GET" style="display: flex; flex-direction: column; gap: 16px;">
                <input type="hidden" name="url" value="artistas">

                <div class="form-group">
                    <label style="display: block; color: #aaa; font-size: 0.85rem; margin-bottom: 5px;">País</label>
                    <select name="pais_origem" style="width: 100%; padding: 10px; background: #111827; border: 1px solid #334155; color: white; border-radius: 8px;">
                        <option value="">Todos os países</option>
                        <?php foreach ($paises as $pais): ?>
                            <option value="<?= (int)$pais['pais_id'] ?>" <?= (!empty($filters['pais_origem']) && $filters['pais_origem'] == $pais['pais_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($pais['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label style="display: block; color: #aaa; font-size: 0.85rem; margin-bottom: 5px;">Gênero Principal</label>
                    <select name="genero_principal" style="width: 100%; padding: 10px; background: #111827; border: 1px solid #334155; color: white; border-radius: 8px;">
                        <option value="">Todos os gêneros</option>
                        <?php foreach ($generos as $genero): ?>
                            <option value="<?= (int)$genero['genero_id'] ?>" <?= (!empty($filters['genero_principal']) && $filters['genero_principal'] == $genero['genero_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($genero['descricao']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <button type="submit" class="btn btn-primary" style="flex: 1; padding: 10px 18px;">Filtrar</button>
                    <a href="?url=artistas" class="btn" style="flex: 1; padding: 10px 18px; background: #555; color: #fff; text-decoration: none; text-align: center;">Limpar</a>
                </div>
            </form>
        </div>
    </aside>

    <?php include __DIR__ . '/../partials/modal_detalhes_artista.php'; ?>
    <?php include __DIR__ . '/../partials/modal_edicao_artista.php'; ?>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
<script src="assets/js/artistas.js"></script>
</body>
</html>