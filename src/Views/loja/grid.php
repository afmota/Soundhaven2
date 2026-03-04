<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoundHaven - Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/loja.css">
</head>
<body>

<header>
    <h1 style="text-align: center; color: var(--accent-color); margin: 40px 0;">SOUNDHAVEN</h1>
</header>

<div class="page-wrapper">
    <div class="spacer-left"></div>

    <div class="main-section">
        <main class="store-grid">
            <?php if (empty($albuns)): ?>
                <p style="grid-column: span 5; text-align: center; color: var(--text-secondary); padding: 50px;">
                    Nenhum álbum encontrado para os filtros aplicados.
                </p>
            <?php else: ?>
                <?php foreach ($albuns as $album): ?>
                    <article class="album-card" 
                             data-album='<?= htmlspecialchars(json_encode($album), ENT_QUOTES, 'UTF-8') ?>'>
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
        </main>

        <nav class="pagination">
            <?php if ($paginaAtual > 1): ?>
                <a href="?<?= http_build_query(array_merge($_GET, ['page' => 1])) ?>">Primeira</a>
            <?php endif; ?>

            <?php for ($i = $inicioPagina; $i <= $fimPagina; $i++): ?>
                <a href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>" 
                   class="<?= $i == $paginaAtual ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <?php if ($paginaAtual < $totalPaginas): ?>
                <a href="?<?= http_build_query(array_merge($_GET, ['page' => $totalPaginas])) ?>">Última</a>
            <?php endif; ?>
        </nav>
    </div>

    <aside class="sidebar-filters">
        <form method="GET" action="">
            <input type="hidden" name="url" value="loja">
            <h3><i class="fa-solid fa-sliders"></i> Filtros</h3>
            
            <div class="filter-group">
                <label>Título do Álbum</label>
                <input type="text" name="titulo" value="<?= htmlspecialchars($filters['titulo'] ?? '') ?>" placeholder="Ex: Master of Puppets">
            </div>

            <div class="filter-group">
                <label>Artista</label>
                <select name="artista_id">
                    <option value="">Todos os Artistas</option>
                    <?php foreach ($artistas as $art): ?>
                        <option value="<?= $art['id'] ?>" <?= ($filters['artista_id'] ?? '') == $art['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($art['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-group">
                <label>Tipo</label>
                <select name="tipo_id">
                    <option value="">Todos os Tipos</option>
                    <?php foreach ($tipos as $t): ?>
                        <option value="<?= $t['id'] ?>" <?= ($filters['tipo_id'] ?? '') == $t['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($t['descricao']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-group">
                <label>Situação</label>
                <select name="situacao_id">
                    <option value="">Todas as Situações</option>
                    <?php foreach ($situacoes as $s): ?>
                        <option value="<?= $s['id'] ?>" <?= ($filters['situacao_id'] ?? '') == $s['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($s['descricao']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-search"><i class="fa-solid fa-magnifying-glass"></i> Filtrar</button>
            <a href="?url=loja" class="btn btn-clear"><i class="fa-solid fa-rotate-left"></i> Limpar Filtros</a>
        </form>
    </aside>
</div>

<div id="albumModal" class="modal">
    <div class="modal-content">
        <span class="modal-close">&times;</span>
        <div style="flex: 0 0 300px;"><img id="modalImg" style="width:100%; border-radius:4px;" src="" alt="Capa"></div>
        <div style="flex: 1;">
            <h2 id="modalTitle" style="color:var(--accent-color); margin-top:0;"></h2>
            <p><span style="color:var(--text-secondary); font-size:0.8em; display:block;">ARTISTA</span><span id="modalArtist"></span></p>
            <p><span style="color:var(--text-secondary); font-size:0.8em; display:block;">GRAVADORA</span><span id="modalLabel"></span></p>
            <p><span style="color:var(--text-secondary); font-size:0.8em; display:block;">LANÇAMENTO</span><span id="modalDate"></span></p>
            <p><span style="color:var(--text-secondary); font-size:0.8em; display:block;">TIPO</span><span id="modalType"></span></p>
            <p><span style="color:var(--text-secondary); font-size:0.8em; display:block;">SITUAÇÃO</span><span id="modalStatus"></span></p>
            
            <div class="modal-actions" style="margin-top:20px; display:flex; gap:10px;">
                <button class="btn btn-acquire"><i class="fa-solid fa-cart-shopping"></i> Adquirir</button>
                <button class="btn btn-edit"><i class="fa-solid fa-pen"></i> Editar</button>
                <form method="POST" id="formDelete">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" id="deleteId">
                    <button type="submit" class="btn btn-delete"><i class="fa-solid fa-trash"></i> Descartar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/loja.js"></script>
</body>
</html>