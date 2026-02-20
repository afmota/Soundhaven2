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
    document.getElementById('colecao-modal-artista').textContent = dados.artista_nome;
    document.getElementById('colecao-modal-formato').textContent = dados.formato_nome || 'Não informado';
    document.getElementById('colecao-modal-gravadora').textContent = dados.gravadora_nome || 'Independente';
    document.getElementById('colecao-modal-aquisicao').textContent = dados.aquisicao ? formatarData(dados.aquisicao) : '--/--/----';

    // 3. Gerenciamento da Seção Inferior (Gêneros e Estilos)
    const secaoAntiga = document.getElementById('secao-inferior-colecao');
    if (secaoAntiga) secaoAntiga.remove();

    const secaoInferior = document.createElement('div');
    secaoInferior.id = 'secao-inferior-colecao';
    
    secaoInferior.innerHTML = `
        <hr class="my-4 opacity-10 border-secondary">
        <div class="row px-2">
            <div id="col-generos" class="col-md-6 mb-3"></div>
            <div id="col-estilos" class="col-md-6 mb-3"></div>
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

    btnEditar.onclick = () => editarItemColecao(dados);
    btnDescartar.onclick = () => confirmarDescarte(dados.id, dados.titulo);

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
 * Lógica para Edição - Preenche o formulário e realiza a troca de modais
 */
function editarItemColecao(dados) {
    if (!dados) return;

    const modalDetalhesEl = document.getElementById('modalColecao');
    const modalEdicaoEl = document.getElementById('modalEdicaoColecao');
    
    if (!modalEdicaoEl) {
        console.error("Erro: Modal de edição não encontrado no DOM.");
        return;
    }

    const instanceDetalhes = bootstrap.Modal.getInstance(modalDetalhesEl);
    const instanceEdicao = bootstrap.Modal.getOrCreateInstance(modalEdicaoEl);

    const campoId = document.getElementById('colecao-edit-id');
    const campoTitulo = document.getElementById('colecao-edit-titulo');
    const campoCapaUrl = document.getElementById('colecao-edit-capa-url');
    const campoPreview = document.getElementById('colecao-edit-preview-capa');
    const campoData = document.getElementById('colecao-edit-data');
    const selectArtista = document.getElementById('colecao-edit-artista');
    const selectGravadora = document.getElementById('colecao-edit-gravadora');
    const selectFormato = document.getElementById('colecao-edit-formato');
    const selectTipo = document.getElementById('colecao-edit-tipo');
    const campoCatalogo = document.getElementById('colecao-edit-catalogo');

    if (campoId) campoId.value = dados.id;
    if (campoTitulo) campoTitulo.value = dados.titulo || '';
    if (campoCapaUrl) campoCapaUrl.value = dados.capa || '';
    if (campoPreview) campoPreview.src = dados.capa || 'img/default-album.jpg';
    
    /**
     * CORREÇÃO: Para o input type="date", precisamos da data completa (YYYY-MM-DD).
     * Utilizamos a nova coluna 'data_lancamento' que foi adicionada à query SQL.
     */
    if (campoData) campoData.value = dados.data_lancamento || '';
    
    if (selectArtista) selectArtista.value = dados.artista_id;
    if (selectGravadora) selectGravadora.value = dados.gravadora_id;
    if (selectFormato) selectFormato.value = dados.formato_id;
    if (selectTipo) selectTipo.value = dados.tipo_id;
    if (campoCatalogo) campoCatalogo.value = dados.numero_catalogo || '';

    if (campoCapaUrl && campoPreview) {
        campoCapaUrl.oninput = function() {
            campoPreview.src = this.value || 'img/default-album.jpg';
        };
    }

    if (instanceDetalhes) instanceDetalhes.hide();
    instanceEdicao.show();
}

/**
 * Inclusão dinâmica de artista sem fechar o modal
 */
async function adicionarArtistaRapido() {
    const nome = prompt("Digite o nome do novo Artista:");
    
    if (!nome || nome.trim() === "") return;

    try {
        const response = await fetch('index.php?action=cadastrar_artista_rapido', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `nome=${encodeURIComponent(nome)}`
        });

        const resultado = await response.json();

        if (resultado.sucesso) {
            const select = document.getElementById('colecao-edit-artista');
            const novaOpcao = new Option(nome, resultado.id, true, true);
            select.add(novaOpcao);
        } else {
            alert("Erro ao cadastrar: " + (resultado.mensagem || "Erro desconhecido"));
        }
    } catch (error) {
        console.error("Erro na requisição:", error);
        alert("Não foi possível conectar ao servidor para salvar o artista.");
    }
}

/**
 * Inclusão dinâmica de gravadora sem fechar o modal
 */
async function adicionarGravadoraRapida() {
    const nome = prompt("Digite o nome da nova Gravadora:");
    
    if (!nome || nome.trim() === "") return;

    try {
        const response = await fetch('index.php?action=cadastrar_gravadora_rapida', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `nome=${encodeURIComponent(nome)}`
        });

        const resultado = await response.json();

        if (resultado.sucesso) {
            const select = document.getElementById('colecao-edit-gravadora');
            const novaOpcao = new Option(nome, resultado.id, true, true);
            select.add(novaOpcao);
        } else {
            alert("Erro ao cadastrar: " + (resultado.mensagem || "Erro desconhecido"));
        }
    } catch (error) {
        console.error("Erro na requisição:", error);
        alert("Não foi possível conectar ao servidor para salvar a gravadora.");
    }
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