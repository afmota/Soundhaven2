<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>SoundHaven - Store</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="store-container">
    <header><h1 style="text-align: center; color: var(--accent-color);">SOUNDHAVEN</h1></header>

    <main class="store-grid">
        <?php foreach ($albuns as $album): ?>
            <article class="album-card" onclick='openModal(<?= json_encode($album) ?>)'>
                <img src="<?= htmlspecialchars($album['capa_url'] ?: 'assets/images/placeholder.jpg') ?>" alt="Capa">
                <div class="album-info">
                    <span class="album-title"><?= htmlspecialchars($album['titulo']) ?></span>
                    <span class="artist-name"><?= htmlspecialchars($album['artista_nome']) ?></span>
                    <span class="release-year"><?= $album['data_lancamento'] ? date('Y', strtotime($album['data_lancamento'])) : 'N/D' ?></span>
                </div>
            </article>
        <?php endforeach; ?>
    </main>

    <div id="albumModal" class="modal">
        <div class="modal-content">
            <span class="modal-close" onclick="closeModal()">&times;</span>
            <img id="modalImg" class="modal-img" src="" alt="Capa">
            <div class="modal-details">
                <h2 id="modalTitle"></h2>
                <p><span class="label">Artista:</span> <span id="modalArtist"></span></p>
                <p><span class="label">Gravadora:</span> <span id="modalLabel"></span></p>
                <p><span class="label">Lançamento:</span> <span id="modalDate"></span></p>
                <p><span class="label">Tipo:</span> <span id="modalType"></span></p>
                <p><span class="label">Situação:</span> <span id="modalStatus"></span></p>
            </div>
        </div>
    </div>

    <nav class="pagination">
        </nav>
</div>

<script>
function openModal(album) {
    document.getElementById('modalTitle').innerText = album.titulo;
    document.getElementById('modalArtist').innerText = album.artista_nome;
    document.getElementById('modalLabel').innerText = album.gravadora_nome || 'N/D';
    document.getElementById('modalDate').innerText = album.data_lancamento || 'N/D';
    document.getElementById('modalType').innerText = album.tipo_desc || 'N/D';
    document.getElementById('modalStatus').innerText = album.situacao_desc || 'N/D';
    document.getElementById('modalImg').src = album.capa_url || 'assets/images/placeholder.jpg';
    document.getElementById('albumModal').style.display = "block";
}

function closeModal() {
    document.getElementById('albumModal').style.display = "none";
}

window.onclick = function(event) {
    if (event.target == document.getElementById('albumModal')) closeModal();
}
</script>

</body>
</html>