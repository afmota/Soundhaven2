// assets/js/adquirir_album.js

// Variável global para controlar o índice das faixas (evita sobrescrever inputs no PHP)
let faixaIndex = 0;

document.addEventListener("DOMContentLoaded", async () => {
    const urlParams = new URLSearchParams(window.location.search);
    const albumId = urlParams.get('id');
    const btnImport = document.getElementById('btn-import-tracks');
    const corpoTabela = document.getElementById('corpoListaFaixas');

    // 1. Carregamento inicial de detalhes (se houver ID na URL)
    if (albumId) {
        try {
            const response = await fetch(`index.php?url=obter_detalhes_album&id=${albumId}`);
            const album = await response.json();

            if (!album.error) {
                document.getElementById('edicaoTitulo').value = album.titulo || '';
                document.getElementById('edicaoCapaUrl').value = album.capa_url || '';
                document.getElementById('edicaoImg').src = album.capa_url || '';
                
                if (album.artista_id) document.getElementById('edicaoArtista').value = album.artista_id;
                if (album.gravadora_id) document.getElementById('edicaoGravadora').value = album.gravadora_id;

                const campoData = document.querySelector('input[name="data_lancamento"]');
                if (campoData) campoData.value = album.data_lancamento || '';
            }
        } catch (error) {
            console.error("Erro ao carregar detalhes:", error);
        }
    }

    // 2. Lógica de Importação do Discogs (Idêntica à Edição)
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
                // Chamada via POST para bater com o Controller
                const response = await fetch(`index.php?url=api_importar_discogs`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ 
                        catalogo: catalogo,
                        titulo: titulo 
                    })
                });

                const data = await response.json();

                if (data.success && data.tracklist) {
                    // Limpa a lista atual antes de inserir as importadas
                    corpoTabela.innerHTML = '';
                    faixaIndex = 0; 

                    const inputDiscogsId = document.getElementById('inputDiscogsId');
                    if(inputDiscogsId) inputDiscogsId.value = data.discogs_id;

                    // Itera sobre as faixas e usa a função de inserção
                    data.tracklist.forEach(track => {
                        inserirLinhaNaTabela(track.numero, track.titulo, track.duracao);
                    });

                    alert(`Sucesso! Importamos ${data.tracklist.length} faixas.`);
                } else {
                    alert("Discogs diz: " + (data.message || "Álbum não encontrado."));
                }

            } catch (error) {
                console.error("Erro na importação:", error);
                alert("Falha ao conectar com a API de importação.");
            } finally {
                btnImport.innerHTML = originalHTML;
                btnImport.disabled = false;
            }
        });
    }

    // 3. Função para inserir linha na tabela (Replicada da edição)
    function inserirLinhaNaTabela(numero, titulo, duracao) {
        const novaLinha = document.createElement('div');
        novaLinha.className = 'faixa-item';

        novaLinha.innerHTML = `
            <input type="hidden" name="faixas[${faixaIndex}][id]" value="new">
            <input type="number" name="faixas[${faixaIndex}][posicao]" value="${numero}" class="input-pos">
            <input type="text" name="faixas[${faixaIndex}][titulo]" value="${titulo}" class="input-titulo">
            <input type="text" name="faixas[${faixaIndex}][duracao]" value="${duracao}" class="input-duracao">
            <button type="button" class="btn-remove-faixa"><i class="fas fa-trash"></i></button>
        `;

        corpoTabela.appendChild(novaLinha);
        faixaIndex++;
    }

    // 4. Lógica para o botão manual de "Adicionar Faixa"
    const btnAddManual = document.getElementById('btnAdicionarFaixa');
    if (btnAddManual) {
        btnAddManual.addEventListener('click', () => {
            const proximaPos = corpoTabela.querySelectorAll('.faixa-item').length + 1;
            inserirLinhaNaTabela(proximaPos, '', '');
            corpoTabela.lastElementChild.querySelector('.input-titulo').focus();
        });
    }

    // 5. Delegação para remover faixas
    corpoTabela.addEventListener('click', (e) => {
        if (e.target.closest('.btn-remove-faixa')) {
            const linha = e.target.closest('.faixa-item');
            if (confirm('Deseja remover esta faixa?')) {
                linha.remove();
            }
        }
    });
});