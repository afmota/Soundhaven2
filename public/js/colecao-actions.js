/**
 * Gerenciador de Ações da Coleção
 * Responsável por popular o modal e gerenciar Edição/Descarte
 */

function abrirModalColecao(dados) {
    // 1. Referências dos Elementos
    const modal = new bootstrap.Modal(document.getElementById('modalColecao'));
    
    // 2. Preenchimento de Dados Básicos
    document.getElementById('colecao-modal-capa').src = dados.capa || 'img/default-album.jpg';
    document.getElementById('colecao-modal-titulo').textContent = dados.titulo;
    document.getElementById('colecao-modal-artista').textContent = dados.artista;
    document.getElementById('colecao-modal-formato').textContent = dados.formato || 'Não informado';
    document.getElementById('colecao-modal-gravadora').textContent = dados.gravadora || 'Independente';
    document.getElementById('colecao-modal-aquisicao').textContent = dados.aquisicao ? formatarData(dados.aquisicao) : '--/--/----';

    // 3. Processamento de Tags (Gêneros e Estilos)
    const containerTags = document.getElementById('colecao-modal-tags');
    containerTags.innerHTML = '';
    
    const todosEstilos = (dados.estilos) ? dados.estilos.split('||') : [];
    todosEstilos.forEach(estilo => {
        const span = document.createElement('span');
        span.className = 'badge rounded-pill bg-dark border border-secondary me-1 mb-1 fw-normal';
        span.style.fontSize = '0.7rem';
        span.textContent = estilo;
        containerTags.appendChild(span);
    });

    // 4. Processamento da Tracklist (Faixas)
    const containerFaixas = document.getElementById('colecao-modal-faixas');
    containerFaixas.innerHTML = '';

    if (dados.faixas) {
        const listaFaixas = dados.faixas.split('||');
        listaFaixas.forEach(faixaStr => {
            const [numero, titulo, duracao] = faixaStr.split('::');
            const li = document.createElement('li');
            li.className = 'd-flex justify-content-between border-bottom border-secondary border-opacity-10 py-1 opacity-75';
            li.innerHTML = `
                <span><span class="text-muted me-2">${numero}.</span> ${titulo}</span>
                <span class="text-muted">${duracao}</span>
            `;
            containerFaixas.appendChild(li);
        });
    } else {
        containerFaixas.innerHTML = '<li class="text-muted italic">Nenhuma faixa cadastrada.</li>';
    }

    // 5. Observações
    const divObs = document.getElementById('colecao-modal-obs');
    if (dados.observacoes && dados.observacoes.trim() !== '') {
        divObs.style.display = 'block';
        divObs.textContent = `"${dados.observacoes}"`;
    } else {
        divObs.style.display = 'none';
    }

    // 6. Configuração dos Botões de Ação (Guardando o ID)
    const btnEditar = document.getElementById('btn-editar-colecao');
    const btnDescartar = document.getElementById('btn-descartar-colecao');

    // Atribuímos o ID do item aos botões para uso futuro
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
    return `${partes[2]}/${partes[1]}/${partes[0]}`;
}

/**
 * Lógica para Edição (Apenas desenho da ação por enquanto)
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
        // Aqui entrará a chamada Fetch para o Controller futuramente
        alert("Item enviado para descarte (Simulação).");
    }
}