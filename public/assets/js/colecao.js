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

    // --- FECHAR MODAL ---
    closeBtn.onclick = () => modal.style.display = 'none';
    window.onclick = (event) => {
        if (event.target == modal) modal.style.display = 'none';
    };

    // --- LÓGICA DO BOTÃO DESCARTAR (EXCLUSÃO LÓGICA) ---
    const btnDescartar = document.getElementById('btnDescartarColecao');
    
    if (btnDescartar) {
        btnDescartar.addEventListener('click', () => {
            // Pegamos o ID do álbum que está aberto no modal
            // (Assumindo que você guardou o midia_id em algum lugar acessível)
            const album = JSON.parse(document.querySelector('.album-card.active')?.getAttribute('data-album') || '{}');
            const midiaId = album.midia_id;
        
            if (!midiaId) return;
        
            if (confirm('Deseja realmente remover este álbum da sua coleção ativa?')) {
                fetch('index.php?url=descartar_album', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `midia_id=${midiaId}`
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // 1. Fecha o modal
                        document.getElementById('modalDetalhesColecao').style.display = 'none';
                        // 2. Remove o card da tela (ou recarrega a página)
                        location.reload(); 
                    } else {
                        alert('Erro ao descartar: ' + data.error);
                    }
                })
                .catch(err => console.error('Erro na requisição:', err));
            }
        });
    }
});