document.addEventListener('DOMContentLoaded', () => {
    const itensNiver = document.querySelectorAll('.abrir-modal-detalhes');
    const modal = document.getElementById('modalDetalhesColecao');

    itensNiver.forEach(item => {
        item.addEventListener('click', () => {
            const album = JSON.parse(item.dataset.album);
            
            // Aqui chamamos a mesma lógica que você já tem no colecao.js
            // Se as funções de preencher modal forem globais, basta usá-las.
            // Caso contrário, usamos este atalho:
            
            exibirDetalhesNoModal(album);
        });
    });
});

// Função para centralizar o preenchimento (pode mover para o functions.js depois)
function exibirDetalhesNoModal(album) {
    const modal = document.getElementById('modalDetalhesColecao');
    if(!modal) return;

    modal.setAttribute('data-current-midia-id', album.id || album.midia_id);
    document.getElementById('detalheCapa').src = album.capa_url || 'assets/images/placeholder.jpg';
    
    const setTxt = (id, text) => { if(document.getElementById(id)) document.getElementById(id).textContent = text || 'N/D'; };
    
    setTxt('detalheTitulo', album.titulo);
    setTxt('detalheArtista', album.artista_nome);
    setTxt('detalheGravadora', album.gravadora_nome);
    setTxt('detalheCatalogo', album.numero_catalogo);
    setTxt('detalheObservacoes', album.observacoes);
    
    // Formatação de Preço (puxando do niver pode vir diferente)
    const preco = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(album.preco || 0);
    setTxt('detalhePreco', preco);

    // Formato
    const tag = document.getElementById('detalheFormatoTag');
    if(tag) {
        tag.textContent = album.formato_nome || '';
        tag.style.backgroundColor = album.formato_cor || '#666';
    }

    // Carregar faixas (usando a função do functions.js que você já tem)
    if (typeof carregarFaixas === 'function') {
        carregarFaixas(album.id || album.midia_id);
    }

    modal.style.display = 'block';
}