/**
 * Gerenciador de Ações da Coleção
 * Responsável por popular o modal e gerenciar Edição/Descarte
 */

function abrirModalColecao(dados) {
    // 1. Referências dos Elementos
    const modalElement = document.getElementById('modalColecao');
    const modalBody = modalElement.querySelector('.modal-body');
    const modal = new bootstrap.Modal(modalElement);
    
    // 2. Preenchimento de Dados Básicos (Esquerda e Direita)
    document.getElementById('colecao-modal-capa').src = dados.capa || 'img/default-album.jpg';
    document.getElementById('colecao-modal-titulo').textContent = dados.titulo;
    document.getElementById('colecao-modal-artista').textContent = dados.artista;
    document.getElementById('colecao-modal-formato').textContent = dados.formato || 'Não informado';
    document.getElementById('colecao-modal-gravadora').textContent = dados.gravadora || 'Independente';
    document.getElementById('colecao-modal-aquisicao').textContent = dados.aquisicao ? formatarData(dados.aquisicao) : '--/--/----';

    // 3. Gerenciamento da Seção Inferior (Após a Linha Horizontal)
    const secaoAntiga = document.getElementById('secao-inferior-colecao');
    if (secaoAntiga) secaoAntiga.remove();

    const secaoInferior = document.createElement('div');
    secaoInferior.id = 'secao-inferior-colecao';
    
    // Injeção da linha e estrutura de colunas para Gêneros e Estilos
    secaoInferior.innerHTML = `
        <hr class="my-4 opacity-10 border-secondary">
        <div class="row px-2">
            <div id="col-generos" class="col-md-6 mb-3"></div>
            <div id="col-estilos" class="col-md-6 mb-3"></div>
        </div>
    `;
    modalBody.appendChild(secaoInferior);

    // Função interna para preencher as colunas com labels e badges
    const preencherTags = (containerId, labelTexto, strDados) => {
        const container = document.getElementById(containerId);
        if (!strDados || strDados.trim() === '') return;

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

    // 4. Processamento da Tracklist
    const containerFaixas = document.getElementById('colecao-modal-faixas');
    containerFaixas.innerHTML = '';

    if (dados.faixas) {
        const listaFaixas = dados.faixas.split('||');
        listaFaixas.forEach(faixaStr => {
            const partes = faixaStr.split('::');
            if(partes.length >= 2) {
                const li = document.createElement('li');
                li.className = 'd-flex justify-content-between border-bottom border-secondary border-opacity-10 py-1 opacity-75';
                li.innerHTML = `
                    <span><span class="text-muted me-2">${partes[0]}.</span> ${partes[1]}</span>
                    <span class="text-muted">${partes[2] || ''}</span>
                `;
                containerFaixas.appendChild(li);
            }
        });
    } else {
        containerFaixas.innerHTML = '<li class="text-muted italic">Nenhuma faixa cadastrada.</li>';
    }

    // 5. Observações
    const divObs = document.getElementById('colecao-modal-obs');
    if (divObs) {
        if (dados.observacoes && dados.observacoes.trim() !== '') {
            divObs.style.display = 'block';
            divObs.textContent = `"${dados.observacoes}"`;
        } else {
            divObs.style.display = 'none';
        }
    }

    // 6. Configuração dos Botões de Ação
    const btnEditar = document.getElementById('btn-editar-colecao');
    const btnDescartar = document.getElementById('btn-descartar-colecao');

    btnEditar.onclick = () => editarItemColecao(dados.id);
    btnDescartar.onclick = () => confirmarDescarte(dados.id, dados.titulo);

    // 7. Exibir o Modal
    modal.show();
}

/**
 * Formata data de YYYY-MM-DD para DD/MM/YYYY
 */
function formatarData(data) {
    if (!data) return '';
    const partes = data.split('-');
    return partes.length === 3 ? `${partes[2]}/${partes[1]}/${partes[0]}` : data;
}

/**
 * Lógica para Edição
 */
function editarItemColecao(id) {
    console.log("Iniciando edição do item ID:", id);
    alert("Função de Edição: Em breve abriremos o formulário para o item " + id);
}

/**
 * Lógica para Descarte com Confirmação
 */
function confirmarDescarte(id, titulo) {
    if (confirm(`ATENÇÃO: Deseja realmente remover "${titulo}" da sua coleção física?\nEsta ação não poderá ser desfeita.`)) {
        console.log("Executando descarte do item ID:", id);
        alert("Item enviado para descarte (Simulação).");
    }
}