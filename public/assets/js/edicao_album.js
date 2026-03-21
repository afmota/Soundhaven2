// assets/js/edicao_album.js

document.addEventListener('DOMContentLoaded', function() {
    const inputCapa = document.getElementById('edicaoCapaUrl');
    const imgPreview = document.getElementById('edicaoImg');

    // Atualiza a prévia da imagem sempre que o usuário mudar a URL
    inputCapa.addEventListener('input', function() {
        const novaUrl = this.value.trim();
        if (novaUrl) {
            imgPreview.src = novaUrl;
        } else {
            imgPreview.src = 'assets/img/default-cover.png'; // Uma imagem padrão caso limpe
        }
    });

    console.log("Módulo de edição carregado de forma independente.");
});

document.addEventListener('DOMContentLoaded', function() {
    
    // Delegar o clique para qualquer ícone de remover tag dentro dos containers
    const containers = ['containerGeneros', 'containerEstilos', 'containerProdutores'];
    
    containers.forEach(id => {
        const container = document.getElementById(id);
        if (!container) return;

        container.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-tag')) {
                const tag = e.target.closest('.tag-item');
                
                // Efeito visual de saída
                tag.style.opacity = '0';
                tag.style.transform = 'scale(0.8)';
                
                setTimeout(() => {
                    tag.remove();
                    // Aqui chamaremos a função para atualizar os inputs ocultos futuramente
                }, 200);
            }
        });
    });
});

document.querySelectorAll('.btn-add-tag').forEach(btn => {
    btn.addEventListener('click', function() {
        const target = this.getAttribute('data-target'); 
        
        // Se o botão não tiver data-target (como o nosso de Importar), ignore este bloco
        if (!target) return; 

        const searchBox = document.getElementById('searchContainer' + target);
        
        if (searchBox) { // Segurança extra: só mexe se o elemento existir
            if (searchBox.style.display === 'none' || searchBox.style.display === '') {
                searchBox.style.display = 'flex';
                searchBox.querySelector('input').focus();
            } else {
                searchBox.style.display = 'none';
            }
        }
    });
});

document.querySelectorAll('.input-search-tag').forEach(input => {
    // Escuta o "Enter" para adicionar a tag
    input.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault(); // Evita que o formulário seja enviado
            adicionarTag(this);
        }
    });

    // Escuta a seleção via datalist (evento change)
    input.addEventListener('change', function() {
        if (this.value.trim() !== '') {
            adicionarTag(this);
        }
    });
});

function adicionarTag(input) {
    const valor = input.value.trim();
    if (valor === '') return;

    // Descobre qual é o tipo (generos, estilos ou produtores)
    // baseado no ID do container pai ou um data-attribute
    const tipo = input.getAttribute('data-tipo'); // Ex: 'generos'
    const container = document.getElementById('container' + capitalize(tipo));

    // 1. Criar o elemento da Tag
    const span = document.createElement('span');
    span.className = 'tag-item';
    span.innerHTML = `
        ${valor}
        <input type="hidden" name="${tipo}[]" value="${valor}">
        <i class="fas fa-times remove-tag"></i>
    `;

    // 2. Adicionar ao container
    container.appendChild(span);

    // 3. Limpar e esconder o campo de busca
    input.value = '';
    input.parentElement.style.display = 'none';
}

function capitalize(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

// Pegamos o número atual de faixas para continuar a contagem
let faixaIndex = document.querySelectorAll('.faixa-item').length;

document.getElementById('btnAdicionarFaixa').addEventListener('click', function() {
    const corpo = document.getElementById('corpoListaFaixas');
    
    // Sugestão de próxima posição (última + 1)
    const proximaPos = corpo.querySelectorAll('.faixa-item').length + 1;

    const novaLinha = document.createElement('div');
    novaLinha.className = 'faixa-item';
    novaLinha.innerHTML = `
        <input type="hidden" name="faixas[${faixaIndex}][id]" value="new">
        <input type="number" name="faixas[${faixaIndex}][posicao]" value="${proximaPos}" class="input-pos">
        <input type="text" name="faixas[${faixaIndex}][titulo]" placeholder="Título da música" class="input-titulo">
        <input type="text" name="faixas[${faixaIndex}][duracao]" placeholder="00:00" class="input-duracao mask-tempo">
        <button type="button" class="btn-remove-faixa"><i class="fas fa-trash"></i></button>
    `;

    corpo.appendChild(novaLinha);
    faixaIndex++;
    
    // Focar no título da nova faixa
    novaLinha.querySelector('.input-titulo').focus();
});

// Delegação de evento para remover a faixa (mesmo as que acabaram de ser criadas)
document.getElementById('corpoListaFaixas').addEventListener('click', function(e) {
    if (e.target.closest('.btn-remove-faixa')) {
        const linha = e.target.closest('.faixa-item');
        
        if (confirm('Deseja remover esta faixa da lista?')) {
            linha.style.opacity = '0';
            setTimeout(() => linha.remove(), 200);
        }
    }
});

document.getElementById('corpoListaFaixas').addEventListener('input', function(e) {
    if (e.target.classList.contains('input-duracao')) {
        let v = e.target.value.replace(/\D/g, ''); // Remove o que não é número
        if (v.length > 4) v = v.substring(0, 4);   // Limita a 4 dígitos
        
        if (v.length >= 3) {
            v = v.substring(0, v.length - 2) + ':' + v.substring(v.length - 2);
        }
        e.target.value = v;
    }
});

// Ajuste na máscara de tempo para suportar HH:MM:SS ou garantir o formato TIME
document.getElementById('corpoListaFaixas').addEventListener('input', function(e) {
    if (e.target.classList.contains('input-duracao')) {
        let v = e.target.value.replace(/\D/g, ''); 
        
        // Se o cara digitar 4 números (MMSS), a gente formata
        if (v.length >= 3 && v.length <= 4) {
            v = v.substring(0, v.length - 2) + ':' + v.substring(v.length - 2);
        } 
        // Se for mais que isso, a gente começa a pensar em HH:MM:SS
        else if (v.length > 4) {
            v = v.substring(0, v.length - 4) + ':' + v.substring(v.length - 4, v.length - 2) + ':' + v.substring(v.length - 2);
        }
        
        e.target.value = v.substring(0, 8); // Limite do formato TIME
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const btnImport = document.getElementById('btn-import-tracks');
    const inputCatalogo = document.getElementById('inputCatalogo');
    const inputTitulo = document.querySelector('input[name="titulo"]'); // Captura o título para o desempate
    const corpoTabela = document.getElementById('corpoListaFaixas');
    const inputDiscogsId = document.getElementById('inputDiscogsId');

    if (btnImport) {
        btnImport.addEventListener('click', async () => {
            const catalogo = inputCatalogo.value.trim();
            const titulo = inputTitulo ? inputTitulo.value.trim() : '';

            if (!catalogo) {
                alert("Opa! Preciso do Número de Catálogo para falar com o Discogs.");
                inputCatalogo.focus();
                return;
            }

            // Feedback visual de carregamento
            const originalHTML = btnImport.innerHTML;
            btnImport.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Buscando...';
            btnImport.disabled = true;

            try {
                // Chamada para o nosso novo Controller/API
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
                    // 1. Limpa a tabela atual (O "Inferno Astral" de jogar fora o antigo)
                    corpoTabela.innerHTML = '';

                    // 2. Atualiza o discogs_id no campo escondido
                    if(inputDiscogsId) inputDiscogsId.value = data.discogs_id;

                    // 3. Popula com as novas faixas
                    data.tracklist.forEach(track => {
                        // Aqui usamos a sua função existente que cria a linha do formulário
                        if (typeof inserirLinhaNaTabela === "function") {
                            inserirLinhaNaTabela(track.numero, track.titulo, track.duracao);
                        }
                    });

                    alert(`Sucesso! Encontramos o álbum no Discogs e importamos ${data.tracklist.length} faixas.`);
                } else {
                    alert("Discogs diz: " + (data.message || "Álbum não encontrado com esses dados. Tente ajustar o catálogo."));
                }

            } catch (error) {
                console.error("Erro na importação:", error);
                alert("Falha crítica ao conectar com a API de importação.");
            } finally {
                btnImport.innerHTML = originalHTML;
                btnImport.disabled = false;
            }
        });
    }

    function inserirLinhaNaTabela(numero, titulo, duracao) {
        const corpo = document.getElementById('corpoListaFaixas');

        const novaLinha = document.createElement('div');
        novaLinha.className = 'faixa-item';

        // Usamos o faixaIndex global para manter a contagem correta dos names[]
        novaLinha.innerHTML = `
            <input type="hidden" name="faixas[${faixaIndex}][id]" value="new">
            <input type="number" name="faixas[${faixaIndex}][posicao]" value="${numero}" class="input-pos">
            <input type="text" name="faixas[${faixaIndex}][titulo]" value="${titulo}" class="input-titulo">
            <input type="text" name="faixas[${faixaIndex}][duracao]" value="${duracao}" class="input-duracao">
            <button type="button" class="btn-remove-faixa"><i class="fas fa-trash"></i></button>
        `;

        corpo.appendChild(novaLinha);
        faixaIndex++; // Incrementa para a próxima não sobrescrever
    }
});