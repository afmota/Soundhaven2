/**
 * SoundHaven - Script Global de Interatividade
 */
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('albumModal');
    const editModal = document.getElementById('editModal');
    const inputCapaUrl = document.getElementById('editModalCapaUrl');
    const imgPreview = document.getElementById('editModalImg');
    
    // Variável de controle para o álbum atual em memória
    let currentAlbumData = null;

    // --- Reatividade da Capa ---
    if (inputCapaUrl && imgPreview) {
        inputCapaUrl.addEventListener('input', (e) => {
            imgPreview.src = e.target.value || 'assets/images/placeholder.jpg';
        });
    }

    // --- Lógica de Abertura do Modal de Detalhes ---
    document.addEventListener('click', (e) => {
        const card = e.target.closest('.album-card');
        if (card) {
            currentAlbumData = JSON.parse(card.getAttribute('data-album'));
            openModal(currentAlbumData);
        }
    });

    // --- Lógica do Botão Editar ---
    const btnOpenEdit = document.getElementById('btnOpenEdit');
    if (btnOpenEdit) {
        btnOpenEdit.addEventListener('click', () => {
            if (currentAlbumData) {
                closeModal();
                openEditModal(currentAlbumData);
            }
        });
    }

    // --- Lógica do Dropdown do Header ---
    const avatarTrigger = document.getElementById('avatarTrigger');
    const dropdown = document.getElementById('myDropdown');

    if (avatarTrigger) {
        avatarTrigger.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdown.classList.toggle('show');
        });
    }

    // Fechamento global
    window.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
        if (e.target === editModal) closeEditModal();
        if (dropdown && !dropdown.contains(e.target) && !avatarTrigger.contains(e.target)) {
            dropdown.classList.remove('show');
        }
    });
});

function openModal(album) {
    document.getElementById('modalTitle').innerText = album.titulo;
    document.getElementById('modalArtist').innerText = album.artista_nome;
    document.getElementById('modalLabel').innerText = album.gravadora_nome || 'N/D';
    document.getElementById('modalDate').innerText = formatDate(album.data_lancamento);
    document.getElementById('modalImg').src = album.capa_url || 'assets/images/placeholder.jpg';
    document.getElementById('modalStatus').innerText = album.situacao_desc || 'N/D';
    document.getElementById('modalType').innerText = album.tipo_desc || 'N/D';
    document.getElementById('deleteId').value = album.id;
    document.getElementById('albumModal').style.display = "block";
}

function closeModal() {
    document.getElementById('albumModal').style.display = "none";
}

function openEditModal(album) {
    // Atualiza o título dinamicamente conforme solicitado
    document.getElementById('editModalHeaderTitle').innerText = `Editar ${album.titulo}`;
    
    // Popula campos existentes
    document.getElementById('editModalImg').src = album.capa_url || 'assets/images/placeholder.jpg';
    document.getElementById('editModalCapaUrl').value = album.capa_url || '';
    
    document.getElementById('editModal').style.display = "block";
}

function closeEditModal() {
    document.getElementById('editModal').style.display = "none";
}

function formatDate(dateStr) {
    if (!dateStr || dateStr === 'N/D') return 'N/D';
    const parts = dateStr.split('-');
    return parts.length !== 3 ? dateStr : `${parts[2]}/${parts[1]}/${parts[0]}`;
}