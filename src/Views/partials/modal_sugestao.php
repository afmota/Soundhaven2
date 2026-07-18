<?php if (!empty($sugestaoDoDia)): ?>
<div id="sugestaoModal" class="modal" style="display: block; z-index: 9999;">
    <div class="modal-content modal-detalhes-loja">
        <span class="modal-close" onclick="fecharSugestao()">&times;</span>
        
        <div class="modal-layout-grid">
            <div class="modal-capa-container" id="sugestaoMediaContainer" style="flex: 0 0 200px; max-width: 200px; transition: all 0.3s ease;">
                <img id="sugestaoCapa" src="<?= htmlspecialchars($sugestaoDoDia['capa_url'] ?: 'assets/images/placeholder.jpg') ?>" alt="Capa" style="width: 100%; aspect-ratio: 1/1; object-fit: cover; border-radius: 4px; border: 1px solid var(--border-color);">
                <div id="sugestaoVideoArea" style="display: none; width: 100%; aspect-ratio: 16/9; border-radius: 8px; overflow: hidden; border: 1px solid var(--border-color);">
                    <iframe id="sugestaoIframe" width="100%" height="100%" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            </div>

            <div class="modal-info-container">
                <h2 style="color: #3c3cff; margin-top: 0; font-size: 1.2em; letter-spacing: 1px;"><i class="fa-solid fa-star"></i> SUGESTÃO DO DIA</h2>
                <h1 style="margin: 10px 0; font-size: 1.8em;"><?= htmlspecialchars($sugestaoDoDia['titulo']) ?></h1>
                
                <div class="info-data-grid">
                    <p><label>ARTISTA</label><span><?= htmlspecialchars($sugestaoDoDia['artista_nome'] ?: 'N/D') ?></span></p>
                    <p><label>GRAVADORA</label><span><?= htmlspecialchars($sugestaoDoDia['gravadora_nome'] ?: 'N/D') ?></span></p>
                    <p><label>LANÇAMENTO</label><span><?= $sugestaoDoDia['data_lancamento'] ? date('d/m/Y', strtotime($sugestaoDoDia['data_lancamento'])) : 'N/D' ?></span></p>
                    <p><label>TIPO</label><span><?= htmlspecialchars($sugestaoDoDia['tipo_descricao'] ?: 'N/D') ?></span></p>
                    <p><label>SITUAÇÃO</label><span><?= htmlspecialchars($sugestaoDoDia['situacao_descricao'] ?: 'N/D') ?></span></p>
                </div>
                
                <div class="modal-actions" style="margin-top: 30px;">
                    <button id="btnOuvirSugestao" type="button" class="btn" style="background-color: #3c3cff; min-width: 150px;" onclick='ouvirSugestao(<?= htmlspecialchars(json_encode($sugestaoDoDia['artista_nome'] ?? ''), ENT_QUOTES, 'UTF-8') ?>, <?= htmlspecialchars(json_encode($sugestaoDoDia['titulo'] ?? ''), ENT_QUOTES, 'UTF-8') ?>)'>
                        <i class="fa-solid fa-play"></i> Ouvir Agora
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function fecharSugestao() {
    document.getElementById('sugestaoModal').style.display = 'none';
    const iframe = document.getElementById('sugestaoIframe');
    if (iframe) {
        iframe.src = '';
    }
}

async function ouvirSugestao(artista, album) {
    const btn = document.getElementById('btnOuvirSugestao');
    if (!btn) return;

    // Guardar o texto original e desabilitar o botão
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Buscando...';

    try {
        const response = await fetch(`index.php?url=buscar_video_youtube&artista=${encodeURIComponent(artista)}&album=${encodeURIComponent(album)}`);
        const data = await response.json();

        if (data && data.success && data.videoId) {
            const mediaContainer = document.getElementById('sugestaoMediaContainer');
            const capa = document.getElementById('sugestaoCapa');
            const videoArea = document.getElementById('sugestaoVideoArea');
            const iframe = document.getElementById('sugestaoIframe');

            if (mediaContainer && videoArea && iframe) {
                // Ajustar estilo do container da mídia para acomodar um player 16:9
                mediaContainer.style.flex = '0 0 350px';
                mediaContainer.style.maxWidth = '350px';

                if (capa) capa.style.display = 'none';
                
                // Atribuir o vídeo correspondente ao iframe
                iframe.src = `https://www.youtube.com/embed/${data.videoId}?autoplay=1`;
                videoArea.style.display = 'block';

                // Atualizar o botão para estado de reprodução
                btn.innerHTML = '<i class="fa-solid fa-music"></i> Reproduzindo';
                btn.style.backgroundColor = '#10B981';
            } else {
                alert('Erro ao inicializar o player de vídeo.');
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        } else {
            alert(data.error || 'Não foi possível encontrar este álbum no YouTube.');
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    } catch (error) {
        console.error('Erro ao buscar sugestão do dia no YouTube:', error);
        alert('Erro ao buscar no YouTube.');
        btn.disabled = false;
        btn.innerHTML = originalText;
    }
}
</script>
<?php endif; ?>