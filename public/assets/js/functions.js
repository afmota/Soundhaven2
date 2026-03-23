// assets/js/comum.js

const formatarTempo = segundos => {
    if (!segundos) return '0:00';
    const min = Math.floor(segundos / 60);
    const seg = segundos % 60;
    return `${min}:${seg.toString().padStart(2, '0')}`;
};

/**
 * Função para renderizar faixas em formato de TABELA (Grid da Coleção)
 */
function renderizarFaixas(faixas, containerId = 'corpoTabelaFaixas') {
    const corpoTabela = document.getElementById(containerId);
    if (!corpoTabela) return;

    corpoTabela.innerHTML = ''; // Limpa o "Carregando..."
    
    if (!faixas || faixas.length === 0) {
        corpoTabela.innerHTML = '<tr><td colspan="3" class="text-center">Nenhuma faixa cadastrada.</td></tr>';
        return;
    }

    faixas.forEach(f => {
        const tr = document.createElement('tr');
        // Mantemos as classes col-pos, col-titulo para o seu CSS original funcionar
        
        const pos = f.numero_faixa || f.position || '-';
        const titulo = f.titulo || f.title || 'Sem título';
        const duracao = f.duracao || f.duration || '--:--';

        tr.innerHTML = `
            <td class="col-pos text-center">${pos}</td>
            <td class="col-titulo">${titulo}</td>
            <td class="col-duracao text-right">${duracao}</td>
        `;
        corpoTabela.appendChild(tr);
    });
}

// assets/js/functions.js

/**
 * Função Universal para inserir linha de faixa na tabela de álbuns
 * @param {string} numero - Posição da faixa
 * @param {string} titulo - Nome da música
 * @param {string} duracao - Tempo (HH:MM:SS)
 * @param {string} containerId - O ID do corpo da tabela (ex: 'corpoListaFaixas')
 */
function inserirLinhaNaTabela(numero, titulo, duracao, containerId = 'corpoListaFaixas') {
    const corpo = document.getElementById(containerId);
    if (!corpo) return;

    // Usamos o contador global 'faixaIndex' que deve estar nos arquivos de página
    const novaLinha = document.createElement('div');
    novaLinha.className = 'faixa-item';

    novaLinha.innerHTML = `
        <input type="hidden" name="faixas[${faixaIndex}][id]" value="new">
        <input type="number" name="faixas[${faixaIndex}][posicao]" value="${numero}" class="input-pos">
        <input type="text" name="faixas[${faixaIndex}][titulo]" value="${titulo}" class="input-titulo">
        <input type="text" name="faixas[${faixaIndex}][duracao]" value="${duracao}" class="input-duracao">
        <button type="button" class="btn-remove-faixa"><i class="fas fa-trash"></i></button>
    `;

    corpo.appendChild(novaLinha);
    faixaIndex++; // Incrementa o índice global
}

/**
 * Inicializa o comportamento de Tags e Máscaras de tempo
 * Pode ser chamada no DOMContentLoaded de qualquer página de formulário
 */
function inicializarComportamentosFormulario() {
    // 1. Gerenciamento de Remoção de Tags (Delegação de evento)
    const containersTags = ['containerGeneros', 'containerEstilos', 'containerProdutores'];
    containersTags.forEach(id => {
        const container = document.getElementById(id);
        if (container) {
            container.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-tag')) {
                    const tag = e.target.closest('.tag-item');
                    tag.style.opacity = '0';
                    tag.style.transform = 'scale(0.8)';
                    setTimeout(() => tag.remove(), 200);
                }
            });
        }
    });

    // 2. Exibir/Esconder caixas de busca de tags
    document.querySelectorAll('.btn-add-tag').forEach(btn => {
        btn.onclick = function() {
            const target = this.getAttribute('data-target'); 
            const searchBox = document.getElementById('searchContainer' + target);
            if (searchBox) {
                const isHidden = searchBox.style.display === 'none' || searchBox.style.display === '';
                searchBox.style.display = isHidden ? 'flex' : 'none';
                if (isHidden) {
                    const input = searchBox.querySelector('input');
                    if (input) input.focus();
                }
            }
        };
    });

    // 3. Adição de Tags via Enter ou Change (Datalist)
    document.querySelectorAll('.input-search-tag').forEach(input => {
        input.onkeypress = function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                processarAdicaoTag(this);
            }
        };
        input.onchange = function() {
            setTimeout(() => { if (this.value.trim() !== '') processarAdicaoTag(this); }, 100);
        };
    });
}

function processarAdicaoTag(input) {
    const valor = input.value.trim();
    if (valor === '') return;

    const tipo = input.getAttribute('data-tipo');
    const container = document.getElementById('container' + (tipo.charAt(0).toUpperCase() + tipo.slice(1)));

    if (!container) return;

    const span = document.createElement('span');
    span.className = 'tag-item';
    span.innerHTML = `${valor} <input type="hidden" name="${tipo}[]" value="${valor}"> <i class="fas fa-times remove-tag"></i>`;

    container.appendChild(span);
    input.value = '';
    const parent = input.closest('.search-tag-container');
    if (parent) parent.style.display = 'none';
}