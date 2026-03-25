document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('modalDetalhesColecao');
    const cards = document.querySelectorAll('.album-card');
    const closeBtn = modal.querySelector('.modal-close'); // Sincronizado com seu CSS
    const btnEditar = document.getElementById('btnEditarColecao');
    const btnDescartar = document.getElementById('btnDescartarColecao');
    const cacheFaixas = {};

    const formatarMoeda = valor =>
        new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        }).format(valor || 0);

    const setTxt = (id, text) => {
        const el = document.getElementById(id);
        if (el) el.textContent = text || 'N/D';
    };

    const renderizarTags = (containerId, dadosString, extraClass = '') => {
        const container = document.getElementById(containerId);
        if (!container) return;
        container.innerHTML = '';
        if (!dadosString) {
            container.innerHTML = '<span class="no-data">Nenhum registro encontrado</span>';
            return;
        }
        dadosString.split('|').forEach(item => {
            const span = document.createElement('span');
            span.className = `tag-item ${extraClass}`;
            span.textContent = item.trim();
            container.appendChild(span);
        });
    };

    const carregarFaixas = async midiaId => {
        // 1. Verifique se o ID no seu HTML é 'corpoTabelaFaixas' ou 'corpoListaFaixas'
        const ID_CONTAINER = 'corpoTabelaFaixas'; 
        const corpoTabela = document.getElementById(ID_CONTAINER);
        
        if (!corpoTabela) return;
    
        if (cacheFaixas[midiaId]) {
            // Passamos o ID correto para a função global
            renderizarFaixas(cacheFaixas[midiaId], ID_CONTAINER);
            return;
        }
    
        corpoTabela.innerHTML = '<tr><td colspan="3">Carregando faixas...</td></tr>';
    
        try {
            const res = await fetch(`index.php?url=buscar_faixas&midia_id=${midiaId}`);
            const faixas = await res.json();
            
            cacheFaixas[midiaId] = faixas;
        
            // CHAMA A FUNÇÃO PASSANDO O ID DO CONTAINER
            if (typeof renderizarFaixas === "function") {
                renderizarFaixas(faixas, ID_CONTAINER);
            } else {
                console.error("A função renderizarFaixas não foi encontrada no functions.js");
            }
        
        } catch (e) {
            corpoTabela.innerHTML = '<tr><td colspan="3">Erro ao carregar faixas</td></tr>';
            console.error("Erro no fetch das faixas:", e);
        }
    };

    cards.forEach(card => {
        card.addEventListener('click', () => {
            const album = JSON.parse(card.dataset.album);
            modal.setAttribute('data-current-midia-id', album.midia_id);
            document.getElementById('detalheCapa').src = album.capa_url || 'assets/images/placeholder.jpg';
            setTxt('detalheTitulo', album.titulo);
            setTxt('detalheArtista', album.artista_nome);
            setTxt('detalheGravadora', album.gravadora_nome);
            setTxt('detalheCatalogo', album.numero_catalogo);
            setTxt('detalhePreco', formatarMoeda(album.preco));
            setTxt('detalheObservacoes', album.observacoes);
            const lanc = album.data_lancamento ? new Date(album.data_lancamento + 'T12:00:00').toLocaleDateString('pt-BR') : 'N/D';
            setTxt('detalheLancamento', lanc);
            const aquis = album.data_aquisicao ? new Date(album.data_aquisicao + 'T12:00:00').toLocaleDateString('pt-BR') : 'N/D';
            setTxt('detalheAquisicao', aquis);
            const tag = document.getElementById('detalheFormatoTag');
            tag.textContent = album.formato_nome;
            tag.style.backgroundColor = album.formato_cor;
            renderizarTags('containerTagsGeneros', album.generos);
            renderizarTags('containerTagsEstilos', album.estilos);
            renderizarTags('containerTagsProdutores', album.produtores);
            carregarFaixas(album.midia_id);
            modal.style.display = 'block';
        });
    });

    btnEditar.onclick = () => {
        const midiaId = modal.getAttribute('data-current-midia-id');
        if (midiaId) window.location.href = `index.php?url=editar_album&midia_id=${midiaId}`;
    };

    btnDescartar.onclick = async () => {
        const midiaId = modal.getAttribute('data-current-midia-id');
        if (!midiaId || !confirm('Remover este álbum da coleção?')) return;
        const formData = new URLSearchParams();
        formData.append('midia_id', midiaId);
        try {
            const res = await fetch('index.php?url=descartar_album', { method: 'POST', body: formData });
            const data = await res.json();
            if (data.success) location.reload();
            else alert(data.error || 'Erro ao descartar');
        } catch (e) { console.error(e); }
    };

    // FUNÇÕES DE FECHAMENTO
    if (closeBtn) {
        closeBtn.onclick = () => modal.style.display = 'none';
    }

    window.onclick = e => {
        if (e.target === modal) modal.style.display = 'none';
    };


});

document.addEventListener('DOMContentLoaded', function() {
    const botoesOuvir = document.querySelectorAll('.btn-ouvir-tag');

    botoesOuvir.forEach(botao => {
        botao.addEventListener('click', function(e) {
            e.stopPropagation(); 
            e.preventDefault(); // Evita qualquer comportamento estranho do botão
            
            const midiaId = this.getAttribute('data-midia-id');
            const btn = this;

            // Feedback visual imediato: adiciona a classe que definimos no CSS
            btn.classList.add('checked');

            fetch(`index.php?url=registrar_audicao&id=${midiaId}`)
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        alert('Erro ao registrar audição.');
                        btn.classList.remove('checked');
                    }
                })
                .catch(err => {
                    console.error('Erro:', err);
                    btn.classList.remove('checked');
                });
        });
    });
});