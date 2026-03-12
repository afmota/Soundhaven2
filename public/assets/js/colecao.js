document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('modalDetalhesColecao');
    const cards = document.querySelectorAll('.album-card');
    const closeBtn = modal.querySelector('.close-modal');

    // --- FUNÇÕES AUXILIARES ---
    const formatarMoeda = (valor) => {
        return new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        }).format(valor || 0);
    };

    // Nova função para limpar o hh:mm:ss do banco
    const formatarTempo = (tempo) => {
        if (!tempo) return '--:--';
        const partes = tempo.split(':');
        if (partes.length === 3) {
            const [h, m, s] = partes;
            return h === '00' ? `${m}:${s}` : `${h}:${m}:${s}`;
        }
        return tempo;
    };

    const setTxt = (id, text) => {
        const el = document.getElementById(id);
        if (el) el.textContent = text || 'N/D';
    };

    const renderizarTags = (containerId, dadosString, extraClass = '') => {
        const container = document.getElementById(containerId);
        if (!container) return;
        container.innerHTML = '';
        if (dadosString) {
            dadosString.split('|').forEach(item => {
                const span = document.createElement('span');
                span.className = `tag-item ${extraClass}`;
                span.textContent = item.trim();
                container.appendChild(span);
            });
        } else {
            container.innerHTML = '<span class="no-data">Nenhum registro encontrado</span>';
        }
    };

    // --- CLIQUE NO CARD (ABRIR MODAL) ---
    cards.forEach(card => {
        card.addEventListener('click', () => {
            const album = JSON.parse(card.getAttribute('data-album'));
            
            // Capa e Textos
            const elCapa = document.getElementById('detalheCapa');
            if (elCapa) elCapa.src = album.capa_url || 'assets/images/placeholder.jpg';

            setTxt('detalheTitulo', album.titulo);
            setTxt('detalheArtista', album.artista_nome);
            setTxt('detalheGravadora', album.gravadora_nome);
            setTxt('detalheCatalogo', album.numero_catalogo);
            setTxt('detalheCondicao', album.condicao);
            setTxt('detalheObservacoes', album.observacoes);
            setTxt('detalhePreco', formatarMoeda(album.preco));

            const anoLanc = album.data_lancamento ? new Date(album.data_lancamento + 'T12:00:00').toLocaleDateString('pt-BR') : 'N/D';
            setTxt('detalheLancamento', anoLanc);
            const dataAqui = album.data_aquisicao ? new Date(album.data_aquisicao + 'T12:00:00').toLocaleDateString('pt-BR') : 'N/D';
            setTxt('detalheAquisicao', dataAqui);

            // Tags
            const tagModal = document.getElementById('detalheFormatoTag');
            if (tagModal) {
                tagModal.textContent = album.formato_nome;
                tagModal.style.backgroundColor = album.formato_cor;
            }

            renderizarTags('containerTagsGeneros', album.generos);
            renderizarTags('containerTagsEstilos', album.estilos, 'style-tag');
            renderizarTags('containerTagsProdutores', album.produtores);

            // Buscar Faixas
            const corpoTabela = document.getElementById('corpoTabelaFaixas');
            corpoTabela.innerHTML = '<tr><td colspan="3">Carregando faixas...</td></tr>';

            fetch(`index.php?url=buscar_faixas&midia_id=${album.midia_id}`)
                .then(res => res.json())
                .then(faixas => {
                    corpoTabela.innerHTML = '';
                    faixas.forEach(f => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${f.numero_faixa}</td>
                            <td>${f.titulo}</td>
                            <td>${formatarTempo(f.duracao)}</td>
                        `;
                        corpoTabela.appendChild(tr);
                    });
                })
                .catch(err => console.error("Erro ao listar faixas:", err));

            modal.style.display = 'block';
        });
    });

    // --- LÓGICA DE ADICIONAR FAIXA ---
    const btnAddFaixa = document.getElementById('btnAdicionarFaixa');
    if (btnAddFaixa) {
        btnAddFaixa.addEventListener('click', () => {
            const corpoTabela = document.getElementById('corpoTabelaFaixas');
            
            if (corpoTabela.querySelector('.no-data') || corpoTabela.innerText.includes('Carregando')) {
                corpoTabela.innerHTML = '';
            }

            const novaLinha = document.createElement('tr');
            const proximoNumero = corpoTabela.querySelectorAll('tr').length + 1;

            novaLinha.innerHTML = `
                <td><input type="text" class="track-input" value="${proximoNumero}" style="width: 30px; text-align: center;"></td>
                <td><input type="text" class="track-input" placeholder="Nome da música..." style="width: 100%;"></td>
                <td>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="text" class="track-input" placeholder="00:00" style="width: 60px; text-align: center;">
                        <button type="button" class="btn-del-track" title="Remover Faixa">×</button>
                    </div>
                </td>
            `;

            novaLinha.querySelector('.btn-del-track').addEventListener('click', () => novaLinha.remove());
            corpoTabela.appendChild(novaLinha);
            novaLinha.querySelectorAll('input')[1].focus();
            
            const container = document.getElementById('area-tabela-faixas');
            if (container) container.scrollTop = container.scrollHeight;
        });
    }

    // --- FECHAR MODAL ---
    closeBtn.onclick = () => modal.style.display = 'none';
    window.onclick = (event) => {
        if (event.target == modal) modal.style.display = 'none';
    };
});