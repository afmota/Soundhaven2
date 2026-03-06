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
    // Debug para você ver no Console se os nomes das chaves estão vindo certos
    console.log("Dados do álbum para edição:", album);

    const setVal = (id, value) => {
        const el = document.getElementById(id);
        if (el) {
            // A MÁGICA: Converte para String e garante que null vire string vazia
            // Isso resolve o problema de o dropdown não selecionar o número vindo do banco
            el.value = (value !== null && value !== undefined) ? String(value) : "";
        } else {
            console.warn(`Atenção: O elemento ID '${id}' não foi encontrado no HTML.`);
        }
    };

    // Título no topo do modal
    const headerTitle = document.getElementById('editModalHeaderTitle');
    if (headerTitle) headerTitle.innerText = `Editar ${album.titulo}`;
    
    // Preview da imagem
    const imgPreview = document.getElementById('editModalImg');
    if (imgPreview) imgPreview.src = album.capa_url || 'assets/images/placeholder.jpg';

    // Preenchimento dos campos
    setVal('editModalAlbumId', album.album_id);
    setVal('editModalCapaUrl', album.capa_url || '');
    setVal('editModalTitulo', album.titulo);
    
    // Dropdowns de Artista e Gravadora
    setVal('editModalArtista', album.artista_id);
    setVal('editModalGravadora', album.gravadora_id);

    // Dropdowns de Tipo e Situação
    // Aqui usamos o que o seu PHP gera: tipo_id e situacao_id (ou situacao)
    setVal('editModalTipo', album.tipo_id);
    setVal('editModalSituacao', album.situacao_id || album.situacao);
    
    // Campo de Data (input type="date" espera YYYY-MM-DD)
    setVal('editModalData', album.data_lancamento || '');
    
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