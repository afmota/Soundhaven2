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