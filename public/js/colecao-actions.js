/**
 * Gerenciador de Ações da Coleção
 * Responsável por popular o modal e gerenciar Edição/Descarte
 */

function abrirModalColecao(dados) {
    const modalElement = document.getElementById('modalColecao');
    const modalBody = modalElement.querySelector('.modal-body');
    const modal = new bootstrap.Modal(modalElement);
    
    document.getElementById('colecao-modal-capa').src = dados.capa || 'img/default-album.jpg';
    document.getElementById('colecao-modal-titulo').textContent = dados.titulo;
    document.getElementById('colecao-modal-artista').textContent = dados.artista_nome;
    document.getElementById('colecao-modal-formato').textContent = dados.formato_nome || 'Não informado';
    document.getElementById('colecao-modal-gravadora').textContent = dados.gravadora_nome || 'Independente';
    document.getElementById('colecao-modal-aquisicao').textContent = dados.aquisicao ? formatarData(dados.aquisicao) : '--/--/----';

    const secaoAntiga = document.getElementById('secao-inferior-colecao');
    if (secaoAntiga) secaoAntiga.remove();

    const secaoInferior = document.createElement('div');
    secaoInferior.id = 'secao-inferior-colecao';
    secaoInferior.innerHTML = `
        <hr class="my-4 opacity-10 border-secondary">
        <div class="row px-2">
            <div id="col-generos" class="col-md-4 mb-3"></div>
            <div id="col-estilos" class="col-md-4 mb-3"></div>
            <div id="col-produtores" class="col-md-4 mb-3"></div>
        </div>
    `;
    modalBody.appendChild(secaoInferior);

    const preencherTags = (containerId, labelTexto, strDados) => {
        const container = document.getElementById(containerId);
        if (!container || !strDados || strDados.trim() === '') return;

        const label = document.createElement('label');
        label.className = 'text-muted text-uppercase d-block mb-2';
        label.style.cssText = 'font-size: 0.65rem; letter-spacing: 1px;';
        label.textContent = labelTexto;
        container.appendChild(label);

        const tags = strDados.split('||');
        tags.forEach(tag => {
            const span = document.createElement('span');
            span.className = 'badge rounded-pill bg-dark border border-secondary me-1 mb-1 fw-normal text-info-emphasis';
            span.style.fontSize = '0.7rem';
            span.textContent = tag;
            container.appendChild(span);
        });
    };

    preencherTags('col-generos', 'Gêneros', dados.generos);
    preencherTags('col-estilos', 'Estilos', dados.estilos);
    preencherTags('col-produtores', 'Produtores', dados.produtores);

    const containerFaixas = document.getElementById('colecao-modal-faixas');
    containerFaixas.innerHTML = '';
    if (dados.faixas) {
        const listaFaixas = dados.faixas.split('||');
        listaFaixas.forEach(faixaStr => {
            const partes = faixaStr.split('::');
            if(partes.length >= 2) {
                const li = document.createElement('li');
                li.className = 'd-flex justify-content-between border-bottom border-secondary border-opacity-10 py-1 opacity-75';
                li.innerHTML = `<span><span class="text-muted me-2">${partes[0]}.</span> ${partes[1]}</span><span class="text-muted">${partes[2] || ''}</span>`;
                containerFaixas.appendChild(li);
            }
        });
    } else {
        containerFaixas.innerHTML = '<li class="text-muted italic">Nenhuma faixa cadastrada.</li>';
    }

    const divObs = document.getElementById('colecao-modal-obs');
    if (divObs) {
        if (dados.observacoes && dados.observacoes.trim() !== '') {
            divObs.style.display = 'block';
            divObs.textContent = `"${dados.observacoes}"`;
        } else {
            divObs.style.display = 'none';
        }
    }

    document.getElementById('btn-editar-colecao').onclick = () => editarItemColecao(dados);
    document.getElementById('btn-descartar-colecao').onclick = () => confirmarDescarte(dados.id, dados.titulo);

    modal.show();
}

function formatarData(data) {
    if (!data) return '';
    const partes = data.split('-');
    return partes.length === 3 ? `${partes[2]}/${partes[1]}/${partes[0]}` : data;
}

/**
 * Lógica para Edição - Popula campos e Badges N:N
 */
function editarItemColecao(dados) {
    if (!dados) return;
    const modalDetalhesEl = document.getElementById('modalColecao');
    const modalEdicaoEl = document.getElementById('modalEdicaoColecao');
    
    bootstrap.Modal.getInstance(modalDetalhesEl)?.hide();
    const instanceEdicao = bootstrap.Modal.getOrCreateInstance(modalEdicaoEl);

    document.getElementById('colecao-edit-id').value = dados.id;
    document.getElementById('colecao-edit-titulo').value = dados.titulo || '';
    document.getElementById('colecao-edit-capa-url').value = dados.capa || '';
    document.getElementById('colecao-edit-preview-capa').src = dados.capa || 'img/default-album.jpg';
    document.getElementById('colecao-edit-data').value = dados.data_lancamento || '';
    document.getElementById('colecao-edit-artista').value = dados.artista_id;
    document.getElementById('colecao-edit-gravadora').value = dados.gravadora_id;
    document.getElementById('colecao-edit-formato').value = dados.formato_id;
    document.getElementById('colecao-edit-tipo').value = dados.tipo_id;
    document.getElementById('colecao-edit-catalogo').value = dados.numero_catalogo || '';

    // Limpar e Popular Badges N:N
    inicializarTagsEdicao('tags-generos', dados.generos, 'generos[]');
    inicializarTagsEdicao('tags-estilos', dados.estilos, 'estilos[]');
    inicializarTagsEdicao('tags-produtores', dados.produtores, 'produtores[]');

    instanceEdicao.show();
}

/**
 * Funções auxiliares para Gestão de Tags N:N
 */
function inicializarTagsEdicao(containerId, strDados, inputName) {
    const container = document.getElementById(containerId);
    container.innerHTML = '';
    if (!strDados) return;
    strDados.split('||').forEach(valor => {
        if(valor.trim()) criarTagDOM(container, valor, inputName);
    });
}

function adicionarTag(select, containerId, inputName) {
    const valor = select.value;
    if (!valor) return;
    const container = document.getElementById(containerId);
    
    // Evita duplicados
    const existentes = Array.from(container.querySelectorAll('input')).map(i => i.value);
    if (!existentes.includes(valor)) {
        criarTagDOM(container, valor, inputName);
    }
    select.value = ""; // Reseta select
}

function criarTagDOM(container, valor, inputName) {
    const span = document.createElement('span');
    span.className = 'badge bg-primary d-flex align-items-center gap-2 mb-1';
    span.style.fontSize = '0.75rem';
    span.innerHTML = `
        ${valor}
        <input type="hidden" name="${inputName}" value="${valor}">
        <i class="bi bi-x-circle cursor-pointer" onclick="this.parentElement.remove()"></i>
    `;
    container.appendChild(span);
}

// Inclusão rápida (Mantendo original)
async function adicionarArtistaRapido() {
    const nome = prompt("Digite o nome do novo Artista:");
    if (!nome) return;
    try {
        const response = await fetch('index.php?action=cadastrar_artista_rapido', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `nome=${encodeURIComponent(nome)}`
        });
        const resultado = await response.json();
        if (resultado.sucesso) {
            const select = document.getElementById('colecao-edit-artista');
            select.add(new Option(nome, resultado.id, true, true));
        }
    } catch (error) { console.error(error); }
}

async function adicionarGravadoraRapida() {
    const nome = prompt("Digite o nome da nova Gravadora:");
    if (!nome) return;
    try {
        const response = await fetch('index.php?action=cadastrar_gravadora_rapida', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `nome=${encodeURIComponent(nome)}`
        });
        const resultado = await response.json();
        if (resultado.sucesso) {
            const select = document.getElementById('colecao-edit-gravadora');
            select.add(new Option(nome, resultado.id, true, true));
        }
    } catch (error) { console.error(error); }
}

function confirmarDescarte(id, titulo) {
    if (confirm(`ATENÇÃO: Deseja realmente remover "${titulo}" da coleção física?`)) {
        alert("Simulação de descarte para ID: " + id);
    }
}