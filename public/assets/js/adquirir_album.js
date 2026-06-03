// assets/js/adquirir_album.js

// Variável global para controlar o índice das faixas
let faixaIndex = 0;

document.addEventListener("DOMContentLoaded", async () => {
    // --- 1. ATIVAÇÃO DOS COMPORTAMENTOS COMUNS ---
    // Aqui o functions.js entra em ação, inclusive com a nova lógica de Gravadora
    if (typeof inicializarComportamentosFormulario === 'function') {
        inicializarComportamentosFormulario();
    }

    // === SOLUÇÃO DO LIVE UPDATE (PREVIEW DA CAPA) ===
    // Escuta a digitação no campo e atualiza a imagem na hora
    const inputCapa = document.getElementById('edicaoCapaUrl');
    const imgPreview = document.getElementById('edicaoImg');

    if (inputCapa && imgPreview) {
        inputCapa.addEventListener('input', () => {
            const url = inputCapa.value.trim();
            imgPreview.src = url ? url : 'assets/images/placeholder.jpg';
        });
    }

    const urlParams = new URLSearchParams(window.location.search);
    const albumId = urlParams.get('id');
    const btnImport = document.getElementById('btn-import-tracks');
    const corpoTabela = document.getElementById('corpoListaFaixas');

    // --- 2. CARREGAMENTO INICIAL DE DETALHES ---
    if (albumId) {
        try {
            const response = await fetch(`index.php?url=obter_detalhes_album&id=${albumId}`);
            const album = await response.json();

            if (!album.error) {
                // Título e Capa
                document.getElementById('edicaoTitulo').value = album.titulo || '';
                document.getElementById('edicaoCapaUrl').value = album.capa_url || '';
                document.getElementById('edicaoImg').src = album.capa_url || 'assets/images/placeholder.jpg';
                
                // Artista (Select)
                const campoArtista = document.getElementById('edicaoArtista');
                if (campoArtista) campoArtista.value = album.artista_id || '';

                // Gravadora (Input + Datalist)
                const campoGravadora = document.getElementById('edicaoGravadora');
                if (campoGravadora) campoGravadora.value = album.gravadora_nome || '';

                // Outros campos
                if (document.getElementById('edicaoLancamento')) document.getElementById('edicaoLancamento').value = album.data_lancamento || '';
                if (document.getElementById('edicaoTipo')) document.getElementById('edicaoTipo').value = album.tipo_id || '';
                if (document.getElementById('edicaoPreco')) document.getElementById('edicaoPreco').value = album.preco_custo || '';
                if (document.getElementById('edicaoCodBarras')) document.getElementById('edicaoCodBarras').value = album.codigo_barras || '';

                // Carrega as faixas se existirem
                if (album.faixas && album.faixas.length > 0) {
                    corpoTabela.innerHTML = '';
                    album.faixas.forEach(f => {
                        inserirLinhaNaTabela(f.posicao, f.titulo, f.duracao);
                    });
                }
            }
        } catch (error) {
            console.error("Erro ao buscar dados do álbum:", error);
        }
    }

    // --- 3. IMPORTAÇÃO VIA DISCOGS ---
    if (btnImport) {
        btnImport.addEventListener('click', async () => {
            const query = document.getElementById('edicaoTitulo').value;
            const artistaSelect = document.getElementById('edicaoArtista');
            const artistaNome = artistaSelect ? artistaSelect.options[artistaSelect.selectedIndex]?.text : '';

            if (!query) {
                alert('Digite ou carregue o título do álbum primeiro.');
                return;
            }

            btnImport.disabled = true;
            btnImport.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Buscando...';

            try {
                const urlSearch = `index.php?url=api_importar_discogs&query=${encodeURIComponent(query)}&artist=${encodeURIComponent(artistaNome)}`;
                const response = await fetch(urlSearch);
                const data = await response.json();

                if (data.success && data.tracks) {
                    corpoTabela.innerHTML = '';
                    data.tracks.forEach(f => {
                        inserirLinhaNaTabela(f.position, f.title, f.duration);
                    });
                    alert(`Sucesso! ${data.tracks.length} faixas importadas.`);
                } else {
                    alert(data.error || 'Nenhuma faixa encontrada para este álbum.');
                }
            } catch (error) {
                console.error('Erro na importação:', error);
                alert('Erro de comunicação com o servidor.');
            } finally {
                btnImport.disabled = false;
                btnImport.innerHTML = '<i class="fa-solid fa-cloud-arrow-down"></i> Importar Faixas via Discogs';
            }
        });
    }

    // --- 4. FUNÇÃO PARA INSERIR LINHA DE FAIXA ---
    function inserirLinhaNaTabela(posicao, titulo, duracao) {
        faixaIndex++;
        const tr = document.createElement('div');
        tr.className = 'faixa-item';
        tr.style.display = 'flex';
        tr.style.gap = '10px';
        tr.style.marginBottom = '8px';
        tr.style.alignItems = 'center';
        tr.style.transition = 'all 0.2s ease';

        tr.innerHTML = `
            <input type="text" name="faixas[${faixaIndex}][posicao]" class="input-posicao" style="width: 50px; text-align: center;" value="${posicao || ''}" placeholder="01">
            <input type="text" name="faixas[${faixaIndex}][titulo]" class="input-titulo" style="flex: 1;" value="${titulo || ''}" placeholder="Nome da música">
            <input type="text" name="faixas[${faixaIndex}][duracao]" class="input-duracao" style="width: 80px; text-align: center;" value="${duracao || ''}" placeholder="00:00" maxlength="5">
            <button type="button" class="btn-remove-faixa" style="background: none; border: none; color: var(--action-destructive); cursor: pointer;"><i class="fa-solid fa-trash"></i></button>
        `;
        corpoTabela.appendChild(tr);
    }

    // Botão Adicionar Faixa Manual
    const btnAddManual = document.getElementById('btn-add-faixa-manual');
    if (btnAddManual) {
        btnAddManual.addEventListener('click', () => {
            const proximaPos = corpoTabela.querySelectorAll('.faixa-item').length + 1;
            inserirLinhaNaTabela(proximaPos, '', '');
            
            const ultimaFaixa = corpoTabela.lastElementChild;
            if (ultimaFaixa) {
                const inputTitulo = ultimaFaixa.querySelector('.input-titulo');
                if (inputTitulo) inputTitulo.focus();
            }
        });
    }

    // --- 5. DELEGAÇÃO DE EVENTOS (REMOÇÃO E MÁSCARA) ---
    corpoTabela.addEventListener('click', (e) => {
        if (e.target.closest('.btn-remove-faixa')) {
            const linha = e.target.closest('.faixa-item');
            if (confirm('Deseja remover esta faixa da lista?')) {
                linha.style.opacity = '0';
                setTimeout(() => linha.remove(), 200);
            }
        }
    });

    corpoTabela.addEventListener('input', (e) => {
        if (e.target.classList.contains('input-duracao')) {
            let v = e.target.value.replace(/\\D/g, ''); 
            if (v.length >= 3 && v.length <= 4) {
                v = v.substring(0, v.length - 2) + ':' + v.substring(v.length - 2);
            } else if (v.length > 4) {
                v = v.substring(0, 2) + ':' + v.substring(2, 4);
            }
            e.target.value = v;
        }
    });
});