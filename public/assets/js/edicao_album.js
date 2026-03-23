// assets/js/edicao_album.js

// Inicializa o índice baseado em quantas faixas já vieram do banco de dados
let faixaIndex = document.querySelectorAll('.faixa-item').length;

document.addEventListener('DOMContentLoaded', function() {
    const inputCapa = document.getElementById('edicaoCapaUrl');
    const imgPreview = document.getElementById('edicaoImg');
    const corpoTabela = document.getElementById('corpoListaFaixas');

    // 1. Prévia da Capa
    if (inputCapa && imgPreview) {
        inputCapa.addEventListener('input', function() {
            const novaUrl = this.value.trim();
            imgPreview.src = novaUrl ? novaUrl : 'assets/img/default-cover.png';
        });
    }

    // 2. Gerenciamento de Tags (Gêneros, Estilos, Produtores)
    const containersTags = ['containerGeneros', 'containerEstilos', 'containerProdutores'];
    containersTags.forEach(id => {
        const container = document.getElementById(id);
        if (!container) return;

        container.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-tag')) {
                const tag = e.target.closest('.tag-item');
                tag.style.opacity = '0';
                tag.style.transform = 'scale(0.8)';
                setTimeout(() => tag.remove(), 200);
            }
        });
    });

    // Abrir/Fechar caixas de busca de tags
    document.querySelectorAll('.btn-add-tag').forEach(btn => {
        btn.addEventListener('click', function() {
            const target = this.getAttribute('data-target'); 
            if (!target) return; 

            const searchBox = document.getElementById('searchContainer' + target);
            if (searchBox) {
                const isHidden = searchBox.style.display === 'none' || searchBox.style.display === '';
                searchBox.style.display = isHidden ? 'flex' : 'none';
                if (isHidden) searchBox.querySelector('input').focus();
            }
        });
    });

    // Input de busca de tags
    document.querySelectorAll('.input-search-tag').forEach(input => {
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                adicionarTag(this);
            }
        });
        input.addEventListener('change', function() {
            if (this.value.trim() !== '') adicionarTag(this);
        });
    });

    // 3. Importação do Discogs
    const btnImport = document.getElementById('btn-import-tracks');
    if (btnImport) {
        btnImport.addEventListener('click', async () => {
            const inputCatalogo = document.getElementById('inputCatalogo');
            const inputTitulo = document.querySelector('input[name="titulo"]');
            const catalogo = inputCatalogo.value.trim();
            const titulo = inputTitulo ? inputTitulo.value.trim() : '';

            if (!catalogo) {
                alert("Opa! Preciso do Número de Catálogo.");
                inputCatalogo.focus();
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
                    faixaIndex = 0; // Reseta para a nova lista importada

                    const inputDiscogsId = document.getElementById('inputDiscogsId');
                    if(inputDiscogsId) inputDiscogsId.value = data.discogs_id;

                    data.tracklist.forEach(track => {
                        // CHAMA A FUNÇÃO GLOBAL DO functions.js
                        inserirLinhaNaTabela(track.numero, track.titulo, track.duracao);
                    });

                    alert(`Sucesso! Importamos ${data.tracklist.length} faixas.`);
                } else {
                    alert("Discogs diz: " + (data.message || "Não encontrado."));
                }
            } catch (error) {
                console.error("Erro:", error);
            } finally {
                btnImport.innerHTML = originalHTML;
                btnImport.disabled = false;
            }
        });
    }

    // 4. Botão Manual e Remoção de Faixas
    const btnAddManual = document.getElementById('btnAdicionarFaixa');
    if (btnAddManual) {
        btnAddManual.addEventListener('click', function() {
            const proximaPos = corpoTabela.querySelectorAll('.faixa-item').length + 1;
            inserirLinhaNaTabela(proximaPos, '', '');
            corpoTabela.lastElementChild.querySelector('.input-titulo').focus();
        });
    }

    corpoTabela.addEventListener('click', function(e) {
        if (e.target.closest('.btn-remove-faixa')) {
            const linha = e.target.closest('.faixa-item');
            if (confirm('Deseja remover esta faixa da lista?')) {
                linha.style.opacity = '0';
                setTimeout(() => linha.remove(), 200);
            }
        }
    });

    // 5. Máscara de Duração (Input Delegation)
    corpoTabela.addEventListener('input', function(e) {
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

// Funções Auxiliares de Tags
function adicionarTag(input) {
    const valor = input.value.trim();
    if (valor === '') return;

    const tipo = input.getAttribute('data-tipo');
    const container = document.getElementById('container' + capitalize(tipo));

    const span = document.createElement('span');
    span.className = 'tag-item';
    span.innerHTML = `
        ${valor}
        <input type="hidden" name="${tipo}[]" value="${valor}">
        <i class="fas fa-times remove-tag"></i>
    `;

    container.appendChild(span);
    input.value = '';
    input.parentElement.style.display = 'none';
}

function capitalize(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}