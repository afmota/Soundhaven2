/**
 * Gerenciamento de ações da vitrine de álbuns 2.0
 */

let albumModalInstance;

const MAPA_TIPOS = { 1: "Estúdio", 2: "EP", 3: "Ao Vivo", 4: "Compilação", 5: "Trilha Sonora" };
const MAPA_SITUACOES = { 1: "Disponível", 2: "Selecionado", 3: "Baixado", 4: "Adquirido", 5: "Descartado" };

document.addEventListener('DOMContentLoaded', function() {
    const modalElement = document.getElementById('albumModal');
    if (modalElement) {
        albumModalInstance = new bootstrap.Modal(modalElement);
    }
});

/**
 * Abre o modal e preenche os dados dinamicamente em quadrantes
 * @param {Object} dados - Objeto JSON com as informações do álbum
 */
function abrirModal(dados) {
    if (!albumModalInstance) return;

    // Cabeçalho e Imagem
    const imgCapa = document.getElementById('modal-capa');
    imgCapa.src = dados.capa;
    imgCapa.alt = dados.titulo;
    document.getElementById('modal-titulo').textContent = dados.titulo;
    document.getElementById('modal-artista').textContent = dados.artista;

    // Quadrantes de Informações
    document.getElementById('modal-ano').textContent = dados.ano;
    document.getElementById('modal-tipo').textContent = MAPA_TIPOS[dados.tipo] || "N/A";
    document.getElementById('modal-situacao').textContent = MAPA_SITUACOES[dados.situacao] || "N/A";
    document.getElementById('modal-inclusao').textContent = dados.inclusao;

    albumModalInstance.show();
}