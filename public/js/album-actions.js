/**
 * Gerenciamento de ações da vitrine de álbuns 2.0
 * Controla os modais de Detalhes e Edição
 */

let albumModalInstance;
let edicaoModalInstance;

// Dicionários de tradução (Sincronizados com o Banco de Dados)
const MAPA_TIPOS = { 
    1: "Estúdio", 
    2: "EP", 
    3: "Ao Vivo", 
    4: "Compilação", 
    5: "Trilha Sonora" 
};

const MAPA_SITUACOES = { 
    1: "Disponível", 
    2: "Selecionado", 
    3: "Baixado", 
    4: "Adquirido", 
    5: "Descartado" 
};

/**
 * Inicialização dos modais após o carregamento do DOM
 */
document.addEventListener('DOMContentLoaded', function() {
    const modalDetalhes = document.getElementById('albumModal');
    if (modalDetalhes) {
        albumModalInstance = new bootstrap.Modal(modalDetalhes);
    }

    const modalEdicao = document.getElementById('modalEdicao');
    if (modalEdicao) {
        edicaoModalInstance = new bootstrap.Modal(modalEdicao);
        
        // Configura o listener de reatividade da imagem de capa
        const inputCapa = document.getElementById('edit-capa-url');
        const imgPreview = document.getElementById('edit-preview-capa');

        if (inputCapa && imgPreview) {
            inputCapa.addEventListener('input', function() {
                imgPreview.src = this.value || 'https://placehold.co/300x300?text=Sem+Capa';
            });
        }
    }
});

/**
 * Preenche o formulário de edição com os dados do álbum
 * @param {Object} dados - Objeto JSON vindo da vitrine
 */
function prepararEdicao(dados) {
    // 1. Identificação e Capa
    const inputId = document.getElementById('edit-id');
    const inputCapa = document.getElementById('edit-capa-url');
    const imgPreview = document.getElementById('edit-preview-capa');
    
    // 2. Campos de Texto, Seleção e Data
    const inputTitulo = document.getElementById('edit-titulo');
    const inputArtista = document.getElementById('edit-artista');
    const inputData = document.getElementById('edit-data');
    const inputTipo = document.getElementById('edit-tipo');
    const inputSituacao = document.getElementById('edit-situacao');

    if (inputId) inputId.value = dados.id;
    
    if (inputCapa && imgPreview) {
        inputCapa.value = dados.capa;
        imgPreview.src = dados.capa;
    }

    if (inputTitulo) inputTitulo.value = dados.titulo;
    if (inputArtista) inputArtista.value = dados.artista_id;
    if (inputData) inputData.value = dados.data;
    if (inputTipo) inputTipo.value = dados.tipo;
    if (inputSituacao) inputSituacao.value = dados.situacao;
}

/**
 * Abre o modal de detalhes e vincula dados para edição
 * @param {Object} dados - Dados enviados pelo card clicado
 */
function abrirModal(dados) {
    if (!albumModalInstance) return;

    // 1. Preenchimento Visual do Modal de Detalhes
    document.getElementById('modal-capa').src = dados.capa;
    document.getElementById('modal-capa').alt = dados.titulo;
    document.getElementById('modal-titulo').textContent = dados.titulo;
    document.getElementById('modal-artista').textContent = dados.artista;

    // 2. Preenchimento dos Quadrantes de Dados
    document.getElementById('modal-ano').textContent = dados.ano;
    document.getElementById('modal-tipo').textContent = MAPA_TIPOS[dados.tipo] || "N/A";
    document.getElementById('modal-situacao').textContent = MAPA_SITUACOES[dados.situacao] || "N/A";
    document.getElementById('modal-inclusao').textContent = dados.inclusao;

    // 3. Vincula a função de preparar edição ao botão específico
    const btnIrParaEdicao = document.getElementById('btn-abrir-edicao');
    if (btnIrParaEdicao) {
        btnIrParaEdicao.onclick = () => prepararEdicao(dados);
    }

    albumModalInstance.show();
}