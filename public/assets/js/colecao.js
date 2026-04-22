// assets/js/colecao.js

document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('modalDetalhesColecao');
    const cards = document.querySelectorAll('.album-card');
    const btnEditar = document.getElementById('btnEditarColecao');
    const btnDescartar = document.getElementById('btnDescartarColecao');

    // Funções utilitárias locais
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

    /**
     * NOTA: A função carregarFaixas foi movida para o functions.js 
     * para permitir o uso compartilhado com o Dashboard.
     */

    // Evento de clique nos cards da Coleção
    cards.forEach(card => {
        card.addEventListener('click', () => {
            const album = JSON.parse(card.dataset.album);
            
            // Define o ID da mídia no modal para ações de edição/descarte
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
            if (tag) {
                tag.textContent = album.formato_nome;
                tag.style.backgroundColor = album.formato_cor;
            }

            // CORRIGIDO: Agora todos usam 'renderizarTags' (nome definido na linha 21)
            renderizarTags('containerTagsGeneros', album.generos);
            renderizarTags('containerTagsEstilos', album.estilos);
            renderizarTags('containerTagsProdutores', album.produtores);
            
            // Chama a função global do functions.js
            if (typeof carregarFaixas === 'function') {
                carregarFaixas(album.midia_id);
            }
            
            modal.style.display = 'block';
        });
    });

    // Ação do botão Editar
    if (btnEditar) {
        btnEditar.onclick = () => {
            const midiaId = modal.getAttribute('data-current-midia-id');
            if (midiaId) window.location.href = `index.php?url=editar_album&midia_id=${midiaId}`;
        };
    }

    // Ação do botão Descartar
    if (btnDescartar) {
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
            } catch (e) { 
                console.error("Erro ao descartar álbum:", e); 
            }
        };
    }
});

// Lógica de "Marcar como Ouvido"
document.addEventListener('DOMContentLoaded', function() {
    const botoesOuvir = document.querySelectorAll('.btn-ouvir-tag');

    botoesOuvir.forEach(botao => {
        botao.addEventListener('click', function(e) {
            e.stopPropagation(); 
            e.preventDefault(); 
            
            const midiaId = this.getAttribute('data-midia-id');
            const btn = this;

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


// --- Lógica de Filtros por Pills ---
document.addEventListener('DOMContentLoaded', () => {
    const pills = document.querySelectorAll('.pill');
    
    pills.forEach(pill => {
        pill.addEventListener('click', function() {
            // Remove a classe 'active' de todas as pills
            document.querySelectorAll('.pill').forEach(p => p.classList.remove('active'));
            // Adiciona no clicado
            this.classList.add('active');
            
            const filtroSelecionado = this.getAttribute('data-filter');
            const todosOsCards = document.querySelectorAll('.album-card');
            
            todosOsCards.forEach(card => {
                // Pega os dados do atributo data-album que você já tem no HTML
                const album = JSON.parse(card.dataset.album);
                
                // Se for 'all', mostra tudo; senão, compara o ID
                if (filtroSelecionado === 'all' || album.tipo_id == filtroSelecionado) {
                    card.style.display = 'flex'; // ou 'block', o que o seu CSS pedir
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
});