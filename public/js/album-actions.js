/**
 * Gerenciamento de ações da vitrine de álbuns 2.0
 * Controla os modais de Detalhes, Edição e Persistência de Dados via AJAX
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
 * Inicialização dos modais e listeners após o carregamento do DOM
 */
document.addEventListener('DOMContentLoaded', function() {
    // Inicializa Modal de Detalhes
    const modalDetalhes = document.getElementById('albumModal');
    if (modalDetalhes) {
        albumModalInstance = new bootstrap.Modal(modalDetalhes);
    }

    // Inicializa Modal de Edição e seus comportamentos
    const modalEdicaoElement = document.getElementById('modalEdicao');
    if (modalEdicaoElement) {
        edicaoModalInstance = new bootstrap.Modal(modalEdicaoElement);
        
        // Listener para reatividade em tempo real da imagem de capa
        const inputCapa = document.getElementById('edit-capa-url');
        const imgPreview = document.getElementById('edit-preview-capa');

        if (inputCapa && imgPreview) {
            inputCapa.addEventListener('input', function() {
                imgPreview.src = this.value || 'https://placehold.co/300x300?text=Sem+Capa';
            });
        }

        // Listener para o evento de SUBMIT do formulário
        const formEdicao = document.getElementById('formEdicaoAlbum');
        if (formEdicao) {
            formEdicao.addEventListener('submit', function(e) {
                e.preventDefault();
                processarEnvioEdicao(this);
            });
        }
    }
});

/**
 * Envia os dados editados para o servidor via Fetch API
 * @param {HTMLFormElement} form - O formulário de edição
 */
async function processarEnvioEdicao(form) {
    // Localiza o botão de submit usando o atributo 'form', 
    // pois ele reside no modal-footer (fora da tag <form>)
    const btnSubmit = document.querySelector('button[form="formEdicaoAlbum"]');
    let originalText = "";

    // Feedback visual de carregamento
    if (btnSubmit) {
        originalText = btnSubmit.innerHTML;
        btnSubmit.disabled = true;
        btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Salvando...';
    }

    const formData = new FormData(form);

    try {
        // Envio assíncrono para o roteador index.php
        const response = await fetch('index.php?action=editar', {
            method: 'POST',
            body: formData
        });

        // Tenta processar a resposta como JSON
        const result = await response.json();

        if (result.success) {
            alert('✅ ' + result.message);
            if (edicaoModalInstance) {
                edicaoModalInstance.hide();
            }
            // Recarrega a página para refletir as alterações na vitrine
            window.location.reload();
        } else {
            alert('❌ Erro: ' + result.message);
        }
    } catch (error) {
        console.error('Erro na requisição:', error);
        alert('❌ Erro de comunicação com o servidor.');
    } finally {
        // Restaura o estado original do botão
        if (btnSubmit) {
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = originalText;
        }
    }
}

/**
 * Preenche o formulário de edição com os dados atuais do álbum
 * @param {Object} dados - Objeto JSON extraído do card da vitrine
 */
function prepararEdicao(dados) {
    // Mapeamento de IDs de campos para valores
    const campos = {
        'edit-id': dados.id,
        'edit-capa-url': dados.capa,
        'edit-titulo': dados.titulo,
        'edit-artista': dados.artista_id,
        'edit-data': dados.data,
        'edit-tipo': dados.tipo,
        'edit-situacao': dados.situacao
    };

    // Preenchimento automatizado dos campos encontrados
    for (const [id, valor] of Object.entries(campos)) {
        const el = document.getElementById(id);
        if (el) el.value = valor;
    }

    // Atualiza a visualização da imagem no modal de edição
    const imgPreview = document.getElementById('edit-preview-capa');
    if (imgPreview) {
        imgPreview.src = dados.capa;
    }
}

/**
 * Abre o modal de detalhes e prepara o vínculo para uma possível edição
 * @param {Object} dados - Dados enviados pelo card clicado
 */
function abrirModal(dados) {
    if (!albumModalInstance) return;

    // Preenchimento do Modal de Detalhes (Visualização)
    document.getElementById('modal-capa').src = dados.capa;
    document.getElementById('modal-capa').alt = dados.titulo;
    document.getElementById('modal-titulo').textContent = dados.titulo;
    document.getElementById('modal-artista').textContent = dados.artista;

    // Preenchimento dos cards de metadados
    document.getElementById('modal-ano').textContent = dados.ano;
    document.getElementById('modal-tipo').textContent = MAPA_TIPOS[dados.tipo] || "N/A";
    document.getElementById('modal-situacao').textContent = MAPA_SITUACOES[dados.situacao] || "N/A";
    document.getElementById('modal-inclusao').textContent = dados.inclusao;

    // Configura o botão de edição para carregar os dados deste álbum específico
    const btnIrParaEdicao = document.getElementById('btn-abrir-edicao');
    if (btnIrParaEdicao) {
        btnIrParaEdicao.onclick = () => prepararEdicao(dados);
    }

    albumModalInstance.show();
}