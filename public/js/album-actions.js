/**
 * Gerencia as interações da Vitrine Soundhaven
 */
function abrirModal(dados) {
    // Mapeamentos de exibição (UX)
    const situacoes = { 1: "Disponível", 2: "Selecionado", 3: "Baixado", 4: "Adquirido", 5: "Descartado" };
    const tipos = { 1: "Estúdio", 2: "EP", 3: "Ao Vivo", 4: "Compilação", 5: "Trilha Sonora" };

    // Preenchimento dos campos do Modal
    document.getElementById('modal-capa').src = dados.capa || 'assets/img/default-cover.jpg';
    document.getElementById('modal-titulo').innerText = dados.titulo;
    document.getElementById('modal-artista').innerText = dados.artista;
    document.getElementById('modal-ano').innerText = dados.ano;
    document.getElementById('modal-tipo').innerText = tipos[dados.tipo] || "N/A";
    document.getElementById('modal-situacao').innerText = situacoes[dados.situacao] || "N/A";
    document.getElementById('modal-inclusao').innerText = dados.inclusao;

    // Instancia e exibe o modal do Bootstrap
    const modalElement = document.getElementById('albumModal');
    const bootstrapModal = bootstrap.Modal.getOrCreateInstance(modalElement);
    bootstrapModal.show();
}