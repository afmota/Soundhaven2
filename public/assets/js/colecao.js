document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('modalDetalhesColecao');
    const cards = document.querySelectorAll('.album-card');
    const closeBtn = modal.querySelector('.close-modal');

    // Função para formatar moeda (BRL)
    const formatarMoeda = (valor) => {
        return new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        }).format(valor || 0);
    };

    cards.forEach(card => {
        card.addEventListener('click', () => {
            const album = JSON.parse(card.getAttribute('data-album'));
            console.log("Dados recebidos no clique:", album); // Mantenha para conferir

            // 1. RESGATANDO A CAPA (O erro do Osvaldo rs)
            const elCapa = document.getElementById('detalheCapa');
            if (elCapa) {
                elCapa.src = album.capa_url || 'assets/images/placeholder.jpg';
            }

            // 2. TEXTOS (Usando a função segura)
            const setTxt = (id, text) => {
                const el = document.getElementById(id);
                if (el) el.textContent = text || 'N/D';
            };

            setTxt('detalheTitulo', album.titulo);
            setTxt('detalheArtista', album.artista_nome);
            setTxt('detalheGravadora', album.gravadora_nome);
            setTxt('detalheCatalogo', album.numero_catalogo);
            setTxt('detalheCondicao', album.condicao);
            setTxt('detalheObservacoes', album.observacoes);

            // 3. DATAS (Com o nome exato que vem do banco)
            // Se no console log não aparecer 'data_lancamento', aqui vai dar N/D
            const anoLanc = album.data_lancamento ? new Date(album.data_lancamento + 'T12:00:00').toLocaleDateString('pt-BR') : 'N/D';
            setTxt('detalheLancamento', anoLanc);

            const dataAqui = album.data_aquisicao ? new Date(album.data_aquisicao + 'T12:00:00').toLocaleDateString('pt-BR') : 'N/D';
            setTxt('detalheAquisicao', dataAqui);

            // 4. PREÇO E TAG
            setTxt('detalhePreco', formatarMoeda(album.preco));

            const tagModal = document.getElementById('detalheFormatoTag');
            if (tagModal) {
                tagModal.textContent = album.formato_nome;
                tagModal.style.backgroundColor = album.formato_cor; // Já vem com # do banco
            }

            modal.style.display = 'block';

            const renderizarTags = (containerId, dadosString, extraClass = '') => {
                const container = document.getElementById(containerId);
                if (!container) return;

                container.innerHTML = ''; // Limpa o anterior

                if (dadosString) {
                    const itens = dadosString.split('|');
                    itens.forEach(item => {
                        const span = document.createElement('span');
                        span.className = `tag-item ${extraClass}`;
                        span.textContent = item.trim();
                        container.appendChild(span);
                    });
                } else {
                    container.innerHTML = '<span class="no-data">Nenhum registro encontrado</span>';
                }
            };

            // Renderiza Gêneros
            renderizarTags('containerTagsGeneros', album.generos);
                    
            // Renderiza Estilos (agora com ID próprio)
            renderizarTags('containerTagsEstilos', album.estilos, 'style-tag');
                    
            // Renderiza Produtores
            renderizarTags('containerTagsProdutores', album.produtores);
        });
    });

    // Fechar modal
    closeBtn.onclick = () => modal.style.display = 'none';
    window.onclick = (event) => {
        if (event.target == modal) modal.style.display = 'none';
    };

    // Função para criar uma linha da tabela
    const criarLinhaFaixa = (faixa = { numero_faixa: '', titulo: '', duracao: '' }) => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td><input type="number" class="track-input" value="${faixa.numero_faixa}" style="width: 35px;"></td>
            <td><input type="text" class="track-input" value="${faixa.titulo}" placeholder="Nome da música..."></td>
            <td><input type="text" class="track-input" value="${faixa.duracao || '00:00'}" placeholder="00:00"></td>
            <td style="text-align: center;">
                <button class="btn-del-track" title="Excluir Faixa">&times;</button>
            </td>
        `;
        return tr;
    };
    
    // No evento de clique do card, após preencher os dados básicos:
    const carregarFaixas = (midiaId) => {
        const corpo = document.getElementById('corpoTabelaFaixas');
        corpo.innerHTML = '<tr><td colspan="4">Carregando faixas...</td></tr>';
    
        fetch(`api/faixas.php?midia_id=${midiaId}`) // Exemplo de endpoint
            .then(res => res.json())
            .then(faixas => {
                corpo.innerHTML = '';
                if (faixas.length > 0) {
                    faixas.forEach(f => corpo.appendChild(criarLinhaFaixa(f)));
                } else {
                    corpo.innerHTML = '<tr><td colspan="4" class="placeholder-text">Nenhuma faixa cadastrada.</td></tr>';
                }
            });
    };    
});