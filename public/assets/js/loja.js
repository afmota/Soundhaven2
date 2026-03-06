/**
 * SoundHaven - Script Global de Interatividade
 */
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('albumModal');
    const editModal = document.getElementById('editModal');
    const inputCapaUrl = document.getElementById('editModalCapaUrl');
    const imgPreview = document.getElementById('editModalImg');
    
    let currentAlbumData = null;

    if (inputCapaUrl && imgPreview) {
        inputCapaUrl.addEventListener('input', (e) => {
            imgPreview.src = e.target.value || 'assets/images/placeholder.jpg';
        });
    }

    document.addEventListener('click', (e) => {
        const card = e.target.closest('.album-card');
        if (card) {
            currentAlbumData = JSON.parse(card.getAttribute('data-album'));
            openModal(currentAlbumData);
        }
    });

    const btnOpenEdit = document.getElementById('btnOpenEdit');
    if (btnOpenEdit) {
        btnOpenEdit.addEventListener('click', () => {
            if (currentAlbumData) {
                closeModal();
                openEditModal(currentAlbumData);
            }
        });
    }

    const avatarTrigger = document.getElementById('avatarTrigger');
    const dropdown = document.getElementById('myDropdown');

    if (avatarTrigger) {
        avatarTrigger.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdown.classList.toggle('show');
        });
    }

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
    
    const deleteIdField = document.getElementById('deleteId');
    if (deleteIdField) {
        deleteIdField.value = album.album_id;
    }

    document.getElementById('albumModal').style.display = "block";
}

function closeModal() {
    document.getElementById('albumModal').style.display = "none";
}

function openEditModal(album) {
    document.getElementById('editModalHeaderTitle').innerText = `Editar ${album.titulo}`;
    document.getElementById('editModalImg').src = album.capa_url || 'assets/images/placeholder.jpg';
    document.getElementById('editModalCapaUrl').value = album.capa_url || '';
    document.getElementById('editModalTitulo').value = album.titulo;
    
    const selectArtista = document.getElementById('editModalArtista');
    if (selectArtista) {
        selectArtista.value = album.artista_id;
    }
    
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