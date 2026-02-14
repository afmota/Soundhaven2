/**
 * Gerenciamento de ações da vitrine de álbuns 2.0
 * Controla os modais de Detalhes, Edição, Descarte, Inclusão e Importação CSV
 */

let albumModalInstance;
let edicaoModalInstance;
let inclusaoModalInstance;
let importacaoModalInstance; // Nova instância

const MAPA_TIPOS = { 1: "Estúdio", 2: "EP", 3: "Ao Vivo", 4: "Compilação", 5: "Trilha Sonora" };
const MAPA_SITUACOES = { 1: "Disponível", 2: "Selecionado", 3: "Baixado", 4: "Adquirido", 5: "Descartado" };

document.addEventListener('DOMContentLoaded', function() {
    // 1. Modal de Detalhes
    const modalDetalhes = document.getElementById('albumModal');
    if (modalDetalhes) albumModalInstance = new bootstrap.Modal(modalDetalhes);

    // 2. Modal de Edição
    const modalEdicaoElement = document.getElementById('modalEdicao');
    if (modalEdicaoElement) {
        edicaoModalInstance = new bootstrap.Modal(modalEdicaoElement);
        
        const inputCapa = document.getElementById('edit-capa-url');
        const imgPreview = document.getElementById('edit-preview-capa');

        if (inputCapa && imgPreview) {
            inputCapa.addEventListener('input', function() {
                imgPreview.src = this.value || 'https://placehold.co/300x300?text=Sem+Capa';
            });
        }

        const formEdicao = document.getElementById('formEdicaoAlbum');
        if (formEdicao) {
            formEdicao.addEventListener('submit', function(e) {
                e.preventDefault();
                processarEnvioEdicao(this);
            });
        }
    }

    // 3. Modal de Inclusão
    const modalInclusaoElement = document.getElementById('modalInclusao');
    if (modalInclusaoElement) {
        inclusaoModalInstance = new bootstrap.Modal(modalInclusaoElement);

        const inputCapaInc = document.getElementById('inc-capa-url');
        const imgPreviewInc = document.getElementById('inc-preview-capa');

        if (inputCapaInc && imgPreviewInc) {
            inputCapaInc.addEventListener('input', function() {
                imgPreviewInc.src = this.value || 'https://placehold.co/300x300?text=Capa+do+Álbum';
            });
        }

        const formInclusao = document.getElementById('formInclusaoAlbum');
        if (formInclusao) {
            formInclusao.addEventListener('submit', function(e) {
                e.preventDefault();
                processarEnvioInclusao(this);
            });
        }
    }

    // 4. Modal de Importação CSV (NOVA INTEGRAÇÃO)
    const modalImportarElement = document.getElementById('modalImportarCSV');
    if (modalImportarElement) {
        importacaoModalInstance = new bootstrap.Modal(modalImportarElement);

        const formImportar = document.getElementById('formImportarCSV');
        if (formImportar) {
            formImportar.addEventListener('submit', function(e) {
                e.preventDefault();
                processarImportacaoCSV(this);
            });
        }
    }
});

/**
 * Envia o arquivo CSV para processamento em lote
 */
async function processarImportacaoCSV(form) {
    const btnSubmit = document.getElementById('btnProcessarCSV');
    const logContainer = document.getElementById('import-log');
    let originalText = "";

    if (btnSubmit) {
        originalText = btnSubmit.innerHTML;
        btnSubmit.disabled = true;
        btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Processando...';
    }

    logContainer.classList.remove('d-none');
    logContainer.innerHTML = '<div class="text-info">Iniciando leitura e upload...</div>';

    const formData = new FormData(form);

    try {
        const response = await fetch('index.php?action=importar', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            logContainer.innerHTML += `<div class="text-success mt-2">✅ ${result.message}</div>`;
            
            if (result.errors && result.errors.length > 0) {
                result.errors.forEach(err => {
                    logContainer.innerHTML += `<div class="text-warning" style="font-size: 0.75rem;">⚠️ ${err}</div>`;
                });
            }

            // Aguarda 2 segundos para o usuário ler o log antes de recarregar
            setTimeout(() => {
                if (importacaoModalInstance) importacaoModalInstance.hide();
                window.location.reload();
            }, 2500);

        } else {
            logContainer.innerHTML += `<div class="text-danger mt-2">❌ Erro: ${result.message}</div>`;
        }
    } catch (error) {
        console.error('Erro na importação:', error);
        logContainer.innerHTML += '<div class="text-danger mt-2">❌ Erro de comunicação com o servidor.</div>';
    } finally {
        if (btnSubmit) {
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = originalText;
        }
    }
}

/**
 * Envia os dados de inclusão para o servidor
 */
async function processarEnvioInclusao(form) {
    const btnSubmit = document.querySelector('button[form="formInclusaoAlbum"]');
    let originalText = "";

    if (btnSubmit) {
        originalText = btnSubmit.innerHTML;
        btnSubmit.disabled = true;
        btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Salvando...';
    }

    const formData = new FormData(form);

    try {
        const response = await fetch('index.php?action=cadastrar', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            alert('✅ ' + result.message);
            if (inclusaoModalInstance) inclusaoModalInstance.hide();
            window.location.reload();
        } else {
            alert('❌ Erro: ' + result.message);
        }
    } catch (error) {
        console.error('Erro na inclusão:', error);
        alert('❌ Erro de comunicação com o servidor.');
    } finally {
        if (btnSubmit) {
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = originalText;
        }
    }
}

/**
 * Envia os dados editados para o servidor (Original preservado)
 */
async function processarEnvioEdicao(form) {
    const btnSubmit = document.querySelector('button[form="formEdicaoAlbum"]');
    let originalText = "";

    if (btnSubmit) {
        originalText = btnSubmit.innerHTML;
        btnSubmit.disabled = true;
        btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Salvando...';
    }

    const formData = new FormData(form);

    try {
        const response = await fetch('index.php?action=editar', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            alert('✅ ' + result.message);
            if (edicaoModalInstance) edicaoModalInstance.hide();
            window.location.reload();
        } else {
            alert('❌ Erro: ' + result.message);
        }
    } catch (error) {
        console.error('Erro na requisição:', error);
        alert('❌ Erro de comunicação.');
    } finally {
        if (btnSubmit) {
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = originalText;
        }
    }
}

/**
 * Realiza a exclusão lógica do álbum (Original preservado)
 */
async function executarDescarte(id) {
    if (!confirm('Tem certeza que deseja descartar este álbum?')) return;

    const formData = new FormData();
    formData.append('id', id);

    try {
        const response = await fetch('index.php?action=descartar', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        if (result.success) {
            alert('🗑️ ' + result.message);
            window.location.reload();
        } else {
            alert('❌ Erro: ' + result.message);
        }
    } catch (error) {
        alert('❌ Erro de comunicação.');
    }
}

/**
 * Preenche o formulário de edição (Original preservado)
 */
function prepararEdicao(dados) {
    const campos = {
        'edit-id': dados.id,
        'edit-capa-url': dados.capa,
        'edit-titulo': dados.titulo,
        'edit-artista': dados.artista_id,
        'edit-data': dados.data,
        'edit-tipo': dados.tipo,
        'edit-situacao': dados.situacao
    };

    for (const [id, valor] of Object.entries(campos)) {
        const el = document.getElementById(id);
        if (el) el.value = valor;
    }

    const imgPreview = document.getElementById('edit-preview-capa');
    if (imgPreview) imgPreview.src = dados.capa;
}

/**
 * Abre o modal de detalhes (Original preservado)
 */
function abrirModal(dados) {
    if (!albumModalInstance) return;

    document.getElementById('modal-capa').src = dados.capa;
    document.getElementById('modal-titulo').textContent = dados.titulo;
    document.getElementById('modal-artista').textContent = dados.artista;
    document.getElementById('modal-ano').textContent = dados.ano;
    document.getElementById('modal-tipo').textContent = MAPA_TIPOS[dados.tipo] || "N/A";
    document.getElementById('modal-situacao').textContent = MAPA_SITUACOES[dados.situacao] || "N/A";
    document.getElementById('modal-inclusao').textContent = dados.inclusao;

    const btnIrParaEdicao = document.getElementById('btn-abrir-edicao');
    if (btnIrParaEdicao) {
        btnIrParaEdicao.onclick = () => prepararEdicao(dados);
    }

    const btnDescartar = document.getElementById('btn-descartar-album');
    if (btnDescartar) {
        btnDescartar.onclick = () => executarDescarte(dados.id);
    }

    albumModalInstance.show();
}