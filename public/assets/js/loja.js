/**
 * SoundHaven - Script Global de Interatividade
 */
document.addEventListener('DOMContentLoaded', () => {
    // --- Referências de Modais ---
    const modal = document.getElementById('albumModal');
    const editModal = document.getElementById('editModal');
    
    // --- Lógica de Abertura do Modal de Detalhes ---
    document.addEventListener('click', (e) => {
        const card = e.target.closest('.album-card');
        if (card) openModal(card);
    });

    // --- Lógica do Botão Editar (Dentro do Modal de Detalhes) ---
    const btnOpenEdit = document.getElementById('btnOpenEdit');
    if (btnOpenEdit) {
        btnOpenEdit.addEventListener('click', () => {
            // Captura a URL da imagem atual antes de fechar o modal de detalhes
            const currentImg = document.getElementById('modalImg').src;
            
            closeModal();
            openEditModal(currentImg);
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

    // Fechamento global (modais e dropdowns)
    window.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
        if (e.target === editModal) closeEditModal();
        
        if (dropdown && !dropdown.contains(e.target) && !avatarTrigger.contains(e.target)) {
            dropdown.classList.remove('show');
        }
    });
});

// Funções Auxiliares - Modal de Detalhes
function openModal(element) {
    const album = JSON.parse(element.getAttribute('data-album'));
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

// Funções Auxiliares - Modal de Edição
function openEditModal(imgSrc) {
    document.getElementById('editModalImg').src = imgSrc;
    document.getElementById('editModalCapaUrl').value = imgSrc;
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