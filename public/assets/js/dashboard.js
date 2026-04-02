document.addEventListener('DOMContentLoaded', () => {
    const itensNiver = document.querySelectorAll('.abrir-modal-detalhes');
    const modal = document.getElementById('modalDetalhesColecao');
    const btnEditar = document.getElementById('btnEditarColecao');
    const btnDescartar = document.getElementById('btnDescartarColecao');

    itensNiver.forEach(item => {
        item.addEventListener('click', () => {
            const album = JSON.parse(item.dataset.album);
            
            // Aqui chamamos a mesma lógica que você já tem no colecao.js
            // Se as funções de preencher modal forem globais, basta usá-las.
            // Caso contrário, usamos este atalho:
            
            exibirDetalhesNoModal(album);
        });
    });

    // Lógica dos botões do Modal dentro do Dashboard
    if (btnEditar) {
        btnEditar.onclick = () => {
            const midiaId = modal.getAttribute('data-current-midia-id');
            if (midiaId) window.location.href = `index.php?url=editar_album&midia_id=${midiaId}`;
        };
    }

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
                console.error("Erro ao descartar álbum no dashboard:", e);
            }
        };
    }

    // 1. Localiza o container que guarda os dados
    const container = document.getElementById('containerChartTopArtistas');
    
    if (container && container.dataset.artistas) {
        try {
            // 2. Faz o parse do JSON que está no atributo data-artistas
            const dadosTopArtistas = JSON.parse(container.dataset.artistas);
            
            if (dadosTopArtistas.length > 0) {
                const ctx = document.getElementById('chartTopArtistas').getContext('2d');
                
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: dadosTopArtistas.map(item => item.artista),
                        datasets: [{
                            label: 'Álbuns',
                            data: dadosTopArtistas.map(item => item.total),
                            // Trocamos a cor única pelo seu array de cores personalizadas
                            backgroundColor: [
                                '#8b5cf6', // Roxo
                                '#f59e0b', // Laranja
                                '#3b82f6', // Azul
                                '#10b981', // Verde
                                '#ef4444'  // Vermelho
                            ],
                            borderColor: [
                                '#8b5cf6',
                                '#f59e0b',
                                '#3b82f6',
                                '#10b981',
                                '#ef4444'
                            ],
                            borderWidth: 1,
                            borderRadius: 5,
                            barThickness: 20 // Opcional: ajusta a espessura para ficar mais elegante
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        // Adicionando o evento de clique nas barras do gráfico
                        onClick: (evt, elements) => {
                            if (elements.length > 0) {
                                const index = elements[0].index;
                                const artistaSelecionado = dadosTopArtistas[index];
                                
                                // Redireciona para a coleção passando o artista_id
                                // Usamos artista_id se disponível, ou id como fallback
                                const id = artistaSelecionado.artista_id || artistaSelecionado.id;
                                if (id) {
                                    window.location.href = `index.php?url=colecao&artista_id=${id}`;
                                }
                            }
                        },
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                grid: { color: 'rgba(255, 255, 255, 0.1)' },
                                ticks: { color: '#aaa', stepSize: 1 }
                            },
                            y: {
                                grid: { display: false },
                                ticks: { color: '#fff' }
                            }
                        }
                    }
                });
            }
        } catch (e) {
            console.error("Erro ao processar dados do gráfico:", e);
        }
    }
});

function exibirDetalhesNoModal(album) {
    const modal = document.getElementById('modalDetalhesColecao');
    if(!modal) return;

    // Seta o ID da mídia para os botões de Editar/Descartar funcionarem
    modal.setAttribute('data-current-midia-id', album.midia_id || album.id);

    document.getElementById('detalheCapa').src = album.capa_url || 'assets/images/placeholder.jpg';
    
    const setTxt = (id, text) => { if(document.getElementById(id)) document.getElementById(id).textContent = text || 'N/D'; };
    
    setTxt('detalheTitulo', album.titulo);
    setTxt('detalheArtista', album.artista_nome);
    setTxt('detalheGravadora', album.gravadora_nome);
    setTxt('detalheCatalogo', album.numero_catalogo);
    setTxt('detalheObservacoes', album.observacoes);
    
    // Datas formatadas
    const formatarData = (dataStr) => dataStr ? new Date(dataStr + 'T12:00:00').toLocaleDateString('pt-BR') : 'N/D';
    setTxt('detalheLancamento', formatarData(album.data_lancamento));
    setTxt('detalheAquisicao', formatarData(album.data_aquisicao));

    // Preço
    const preco = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(album.preco || 0);
    setTxt('detalhePreco', preco);

    // Formato
    const tag = document.getElementById('detalheFormatoTag');
    if(tag) {
        tag.textContent = album.formato_nome || '';
        tag.style.backgroundColor = album.formato_cor || '#666';
    }

    // Tags (Gêneros, Estilos, Produtores)
    const renderTags = (containerId, str) => {
        const c = document.getElementById(containerId);
        if(!c) return;
        c.innerHTML = '';
        if(!str) { c.innerHTML = '<span class="no-data">N/D</span>'; return; }
        str.split('|').forEach(t => {
            const s = document.createElement('span');
            s.className = 'tag-item';
            s.textContent = t.trim();
            c.appendChild(s);
        });
    };

    renderTags('containerTagsGeneros', album.generos);
    renderTags('containerTagsEstilos', album.estilos);
    renderTags('containerTagsProdutores', album.produtores);

    // Carregar faixas (a função deve estar global ou acessível)
    if (typeof carregarFaixas === 'function') {
        carregarFaixas(album.midia_id || album.id);
    }

    modal.style.display = 'block';
}