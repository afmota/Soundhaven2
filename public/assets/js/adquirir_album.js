// assets/js/adquirir_album.js

// Variável global para controlar o índice das faixas
let faixaIndex = 0;

document.addEventListener("DOMContentLoaded", async () => {
    // --- 1. ATIVAÇÃO DOS COMPORTAMENTOS COMUNS ---
    // Aqui o functions.js entra em ação, inclusive com a nova lógica de Gravadora
    if (typeof inicializarComportamentosFormulario === 'function') {
        inicializarComportamentosFormulario();
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

                // --- GRAVADORA ---
                // Preenchemos o Nome e o ID. O functions.js cuidará de manter o ID 
                // sincronizado se o usuário mudar o nome depois.
                const inputGravNome = document.getElementById('edicaoGravadoraNome');
                const inputGravId = document.getElementById('edicaoGravadoraId');
                
                if (inputGravNome) inputGravNome.value = album.gravadora_nome || '';
                if (inputGravId) inputGravId.value = album.gravadora_id || '';

                // Data de Lançamento
                const campoData = document.querySelector('input[name="data_lancamento"]');
                if (campoData) campoData.value = album.data_lancamento || '';
            }
        } catch (error) {
            console.error("Erro ao carregar detalhes:", error);
        }
    }

    // --- 3. IMPORTAÇÃO DO DISCOGS ---
    if (btnImport) {
        btnImport.addEventListener('click', async () => {
            const catalogo = document.getElementById('inputCatalogo').value.trim();
            const titulo = document.getElementById('edicaoTitulo').value.trim();

            if (!catalogo) {
                alert("Opa! Preciso do Número de Catálogo para falar com o Discogs.");
                document.getElementById('inputCatalogo').focus();
                return;
            }

            const originalHTML = btnImport.innerHTML;
            btnImport.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Buscando...';
            btnImport.disabled = true;

            try {
                const response = await fetch(`index.php?url=api_importar_discogs`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ catalogo, titulo })
                });

                const data = await response.json();

                if (data.success && data.tracklist) {
                    corpoTabela.innerHTML = '';
                    faixaIndex = 0; 

                    const inputDiscogsId = document.getElementById('inputDiscogsId');
                    if(inputDiscogsId) inputDiscogsId.value = data.discogs_id;

                    data.tracklist.forEach(track => {
                        inserirLinhaNaTabela(track.numero, track.titulo, track.duracao);
                    });

                    alert(`Sucesso! Importamos ${data.tracklist.length} faixas.`);
                } else {
                    alert("Discogs diz: " + (data.message || "Álbum não encontrado."));
                }
            } catch (error) {
                console.error("Erro na importação:", error);
            } finally {
                btnImport.innerHTML = originalHTML;
                btnImport.disabled = false;
            }
        });
    }

    // --- 4. BOTÃO MANUAL "ADICIONAR FAIXA" ---
    const btnAddManual = document.getElementById('btnAdicionarFaixa');
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
    // Centralizamos aqui as ações repetitivas das faixas
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
            let v = e.target.value.replace(/\D/g, ''); 
            if (v.length >= 3 && v.length <= 4) {
                v = v.substring(0, v.length - 2) + ':' + v.substring(v.length - 2);
            } else if (v.length > 4) {
                v = v.substring(0, v.length - 4) + ':' + v.substring(v.length - 4, v.length - 2) + ':' + v.substring(v.length - 2);
            }
            e.target.value = v.substring(0, 8);
        }
    });
});