/**
 * SoundHaven - Script de Interatividade da Loja
 */

document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('albumModal');
    
    // 1. Delegação de Evento para abertura do Modal
    document.addEventListener('click', (e) => {
        const card = e.target.closest('.album-card');
        if (card) {
            openModal(card);
        }
    });

    // 2. Fechamento do Modal (Botão X)
    const closeBtn = document.querySelector('.modal-close');
    if (closeBtn) {
        closeBtn.addEventListener('click', closeModal);
    }

    // 3. Fechamento ao clicar fora do conteúdo
    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeModal();
        }
    });
});

/**
 * Preenche e exibe o modal
 */
function openModal(element) {
    const albumData = element.getAttribute('data-album');
    if (!albumData) return;

    try {
        const album = JSON.parse(albumData);
        
        document.getElementById('modalTitle').innerText = album.titulo;
        document.getElementById('modalArtist').innerText = album.artista_nome;
        document.getElementById('modalLabel').innerText = album.gravadora_nome || 'N/D';
        document.getElementById('modalDate').innerText = formatDate(album.data_lancamento);
        document.getElementById('modalType').innerText = album.tipo_desc || 'N/D';
        document.getElementById('modalStatus').innerText = album.situacao_desc || 'N/D';
        document.getElementById('modalImg').src = album.capa_url || 'assets/images/placeholder.jpg';
        document.getElementById('deleteId').value = album.id;

        document.getElementById('albumModal').style.display = "block";
    } catch (error) {
        console.error("Erro ao processar dados do álbum:", error);
    }
}

function closeModal() {
    document.getElementById('albumModal').style.display = "none";
}

function formatDate(dateStr) {
    if (!dateStr || dateStr === 'N/D') return 'N/D';
    const parts = dateStr.split('-');
    return parts.length !== 3 ? dateStr : `${parts[2]}/${parts[1]}/${parts[0]}`;
}