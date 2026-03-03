<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoundHaven - Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="store-container">
    <header><h1 style="text-align: center; color: var(--accent-color);">SOUNDHAVEN</h1></header>

    <main class="store-grid">
        <?php foreach ($albuns as $album): ?>
            <article class="album-card" 
                     data-album='<?= htmlspecialchars(json_encode($album), ENT_QUOTES, 'UTF-8') ?>'
                     onclick="openModal(this)">
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
            <div class="modal-left">
                <img id="modalImg" class="modal-img" src="" alt="Capa">
            </div>
            <div class="modal-details">
                <h2 id="modalTitle"></h2>
                <p><span class="label">Artista</span> <span id="modalArtist"></span></p>
                <p><span class="label">Gravadora</span> <span id="modalLabel"></span></p>
                <p><span class="label">Lançamento</span> <span id="modalDate"></span></p>
                <p><span class="label">Tipo</span> <span id="modalType"></span></p>
                <p><span class="label">Situação</span> <span id="modalStatus"></span></p>
                
                <div class="modal-actions">
                    <button class="btn btn-acquire">
                        <i class="fa-solid fa-cart-shopping"></i> Adquirir
                    </button>

                    <button class="btn btn-edit">
                        <i class="fa-solid fa-pen-to-square"></i> Editar
                    </button>

                    <form method="POST" id="formDelete" onsubmit="return confirm('Deseja realmente remover este item da loja?')">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" id="deleteId">
                        <button type="submit" class="btn btn-delete">
                            <i class="fa-solid fa-trash-can"></i> Descartar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <nav class="pagination">
        <?php if ($paginaAtual > 1): ?>
            <a href="?url=loja&page=1">Primeira</a>
        <?php endif; ?>
        <?php for ($i = $inicioPagina; $i <= $fimPagina; $i++): ?>
            <a href="?url=loja&page=<?= $i ?>" class="<?= $i == $paginaAtual ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
        <?php if ($paginaAtual < $totalPaginas): ?>
            <a href="?url=loja&page=<?= $totalPaginas ?>">Última</a>
        <?php endif; ?>
    </nav>
</div>

<script>
function formatDate(dateStr) {
    if (!dateStr || dateStr === 'N/D') return 'N/D';
    const parts = dateStr.split('-');
    if (parts.length !== 3) return dateStr;
    return `${parts[2]}/${parts[1]}/${parts[0]}`;
}

function openModal(element) {
    const album = JSON.parse(element.getAttribute('data-album'));
    document.getElementById('modalTitle').innerText = album.titulo;
    document.getElementById('modalArtist').innerText = album.artista_nome;
    document.getElementById('modalLabel').innerText = album.gravadora_nome || 'N/D';
    document.getElementById('modalDate').innerText = formatDate(album.data_lancamento);
    document.getElementById('modalType').innerText = album.tipo_desc || 'N/D';
    document.getElementById('modalStatus').innerText = album.situacao_desc || 'N/D';
    document.getElementById('modalImg').src = album.capa_url || 'assets/images/placeholder.jpg';
    document.getElementById('deleteId').value = album.id;
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