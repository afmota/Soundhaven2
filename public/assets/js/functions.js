// assets/js/functions.js

/**
 * 1. SINCRONIZAÇÃO DE GRAVADORA (ID VS NOME)
 * Esta lógica roda em todas as páginas via delegação, mas só age se encontrar os campos.
 */
document.addEventListener('input', function(e) {
    if (e.target && (e.target.id === 'edicaoGravadora' || e.target.id === 'edicaoGravadoraNome')) {
        
        const inputNome = e.target;
        const nomeDigitado = inputNome.value;
        
        const inputHidden = document.getElementById('idGravadoraHidden') || document.getElementById('edicaoGravadoraId');
        const datalist = document.getElementById('listaSugestoesGravadoras');
        
        if (inputHidden && datalist) {
            const opcao = Array.from(datalist.options).find(opt => opt.value === nomeDigitado);

            if (opcao) {
                inputHidden.value = opcao.getAttribute('data-id');
            } else {
                inputHidden.value = '';
            }
            console.log("Sync Gravadora - ID:", inputHidden.value, "| Nome:", nomeDigitado);
        }
    }
});

/**
 * 2. UTILITÁRIOS DE TEMPO E RENDERIZAÇÃO
 */
const formatarTempo = segundos => {
    if (!segundos) return '0:00';
    const min = Math.floor(segundos / 60);
    const seg = segundos % 60;
    return `${min}:${seg.toString().padStart(2, '0')}`;
};

function renderizarFaixas(faixas, containerId = 'corpoTabelaFaixas') {
    const corpoTabela = document.getElementById(containerId);
    if (!corpoTabela) return;

    corpoTabela.innerHTML = ''; 
    
    if (!faixas || faixas.length === 0) {
        corpoTabela.innerHTML = '<tr><td colspan="3" class="text-center">Nenhuma faixa cadastrada.</td></tr>';
        return;
    }

    faixas.forEach(f => {
        const tr = document.createElement('tr');
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

/**
 * 3. FORMULÁRIOS: FAIXAS E TAGS
 */
function inserirLinhaNaTabela(numero, titulo, duracao, containerId = 'corpoListaFaixas') {
    const corpo = document.getElementById(containerId);
    if (!corpo) return;

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
    faixaIndex++; 
}

function inicializarComportamentosFormulario() {
    const containersTags = ['containerGeneros', 'containerEstilos', 'containerProdutores'];
    containersTags.forEach(id => {
        const container = document.getElementById(id);
        if (container) {
            container.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-tag')) {
                    const tag = e.target.closest('.tag-item');
                    tag.style.opacity = '0';
                    setTimeout(() => tag.remove(), 200);
                }
            });
        }
    });

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

/**
 * 4. COMPONENTES GLOBAIS (AVATAR, DROPDOWNS, MODAIS)
 */
function inicializarComponentesGlobais() {
    const avatarTrigger = document.getElementById('avatarTrigger');
    const dropdown = document.getElementById('myDropdown');

    if (avatarTrigger && dropdown) {
        avatarTrigger.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdown.classList.toggle('show');
        });
    }

    // Gerenciamento Universal de Cliques (Dropdown e Fechamento de Modal)
    window.addEventListener('click', (e) => {
        // Fecha dropdown do avatar
        if (dropdown && dropdown.classList.contains('show')) {
            if (!dropdown.contains(e.target) && !avatarTrigger.contains(e.target)) {
                dropdown.classList.remove('show');
            }
        }

        // Fecha Modal de Detalhes (Universal)
        const modal = document.getElementById('modalDetalhesColecao');
        if (modal && modal.style.display === 'block') {
            const closeBtn = modal.querySelector('.modal-close');
            if (e.target === modal || e.target === closeBtn) {
                modal.style.display = 'none';
            }
        }
    });
}

document.addEventListener('DOMContentLoaded', inicializarComponentesGlobais);