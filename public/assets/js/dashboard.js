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

    // --- Gráfico Top 5 Gravadoras ---
    const containerGravadoras = document.getElementById('containerChartTopGravadoras');

    if (containerGravadoras && containerGravadoras.dataset.gravadoras) {
        try {
            const dadosTopGravadoras = JSON.parse(containerGravadoras.dataset.gravadoras);

            if (dadosTopGravadoras.length > 0) {
                const ctxGravadoras = document.getElementById('chartTopGravadoras').getContext('2d');

                new Chart(ctxGravadoras, {
                    type: 'bar',
                    data: {
                        labels: dadosTopGravadoras.map(item => item.gravadora),
                        datasets: [{
                            label: 'Mídias',
                            data: dadosTopGravadoras.map(item => item.total),
                            backgroundColor: [
                                '#3b82f6', // Azul
                                '#10b981', // Verde
                                '#f59e0b', // Laranja
                                '#8b5cf6', // Roxo
                                '#ef4444'  // Vermelho
                            ],
                            borderWidth: 0,
                            borderRadius: 5,
                            barThickness: 20
                        }]
                    },
                    options: {
                        indexAxis: 'y', // Mantém o estilo horizontal
                        responsive: true,
                        maintainAspectRatio: false,
                        onClick: (evt, elements) => {
                            if (elements.length > 0) {
                                const index = elements[0].index;
                                const gravadoraSelecionada = dadosTopGravadoras[index];
                                const id = gravadoraSelecionada.gravadora_id;
                                
                                if (id) {
                                    // Redireciona para a coleção filtrando pela gravadora
                                    window.location.href = `index.php?url=colecao&gravadora_id=${id}`;
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
            console.error("Erro ao processar dados das gravadoras:", e);
        }
    }

    // --- Gráfico Top 5 Produtores ---
    const containerProdutores = document.getElementById('containerChartTopProdutores');

    if (containerProdutores && containerProdutores.dataset.produtores) {
        try {
            const dadosTopProdutores = JSON.parse(containerProdutores.dataset.produtores);

            if (dadosTopProdutores.length > 0) {
                const ctxProdutores = document.getElementById('chartTopProdutores').getContext('2d');

                new Chart(ctxProdutores, {
                    type: 'bar',
                    data: {
                        labels: dadosTopProdutores.map(item => item.produtor),
                        datasets: [{
                            label: 'Mídias',
                            data: dadosTopProdutores.map(item => item.total),
                            backgroundColor: [
                                '#ef4444',  // Vermelho
                                '#f59e0b', // Laranja
                                '#10b981', // Verde
                                '#3b82f6', // Azul
                                '#8b5cf6' // Roxo
                            ],
                            borderWidth: 0,
                            borderRadius: 5,
                            barThickness: 20
                        }]
                    },
                    options: {
                        indexAxis: 'y', // Mantém o estilo horizontal
                        responsive: true,
                        maintainAspectRatio: false,
                        onClick: (evt, elements) => {
                            if (elements.length > 0) {
                                const index = elements[0].index;
                                const produtorSelecionado = dadosTopProdutores[index];
                                const id = produtorSelecionado.produtor_id;
                               
                                if (id) {
                                    // Redireciona passando o parâmetro produtor_id
                                    window.location.href = `index.php?url=colecao&produtor_id=${id}`;
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
            console.error("Erro ao processar dados dos produtores:", e);
        }
    }

    const containerFormatos = document.getElementById('containerChartFormatos');
    if (containerFormatos) {
        const dadosRaw = JSON.parse(containerFormatos.dataset.formatos);
        
        // Mapeia os labels (CD, LP) e os valores (quantidades)
        const labels = dadosRaw.map(item => item.formato);
        const valores = dadosRaw.map(item => item.total);

        new Chart(document.getElementById('chartFormatos'), {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: valores,
                    backgroundColor: ['#3c3cff', '#338d33'], // Azul para CD, Verde para LP
                    borderWidth: 0,
                    hoverOffset: 5
                }]
            },
            options: {
                cutout: '75%', // Define o tamanho do buraco central
                maintainAspectRatio: false,
                // Implementação do clique para filtrar a coleção
                onClick: (evt, elements) => {
                    if (elements.length > 0) {
                        const index = elements[0].index;
                        const formatoNome = labels[index];

                        // Mapeamento de ID baseado no nome do formato
                        // Vinil/LP costuma ser 1, CD costuma ser 2 no seu banco
                        let formatoId = (formatoNome === 'LP' || formatoNome === 'Vinil') ? 1 : 2;

                        window.location.href = `index.php?url=colecao&formato_id=${formatoId}`;
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            color: '#fff',
                            font: { size: 10 },
                            padding: 10,
                            usePointStyle: true
                        }
                    },
                    tooltip: {
                        enabled: true
                    }
                }
            }
        });
    }

    // --- Lógica do Modal de Abrangência (Linha do Tempo) ---
    const btnAnos = document.getElementById('btnAbrirModalAnos');
    const modalAnos = document.getElementById('modalLinhaTempo');
    const closeAnos = document.querySelector('.close-modal-anos');
    let chartAnosInstance = null;

    if (btnAnos && modalAnos) {
        // Usando addEventListener para garantir que não sobrescreva outros cliques
        btnAnos.addEventListener('click', function() {
            console.log("Clique detectado! Abrindo modal...");
            
            modalAnos.style.display = 'block';
            
            const dadosRaw = this.dataset.anos;
            if (!dadosRaw) {
                console.error("Dados 'data-anos' não encontrados no card.");
                return;
            }

            const dadosAnos = JSON.parse(dadosRaw);
            const canvas = document.getElementById('chartLinhaTempo');
            
            if (!canvas) {
                console.error("Canvas 'chartLinhaTempo' não encontrado dentro do modal!");
                return;
            }

            const ctx = canvas.getContext('2d');

            if (chartAnosInstance) chartAnosInstance.destroy();

            chartAnosInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: dadosAnos.map(d => d.ano),
                    datasets: [{
                        label: 'Álbuns',
                        data: dadosAnos.map(d => d.total),
                        backgroundColor: '#3b82f6',
                        borderRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    onClick: (evt, elements) => {
                        if (elements.length > 0) {
                            const index = elements[0].index;
                            const ano = dadosAnos[index].ano;
                            window.location.href = `index.php?url=colecao&ano=${ano}`;
                        }
                    },
                    scales: {
                        y: { 
                            beginAtZero: true, 
                            grid: { color: 'rgba(255, 255, 255, 0.1)' },
                            ticks: { color: '#aaa', stepSize: 1 } 
                        },
                        x: { 
                            grid: { display: false },
                            ticks: { color: '#fff' } 
                        }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        });
    } else {
        console.error("Erro: btnAnos ou modalAnos não encontrados no DOM.", { btnAnos, modalAnos });
    }

    // Fechar Modal
    if (closeAnos) {
        closeAnos.onclick = () => modalAnos.style.display = 'none';
    }

    window.addEventListener('click', (e) => {
        if (modalAnos && e.target == modalAnos) modalAnos.style.display = 'none';
    });
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