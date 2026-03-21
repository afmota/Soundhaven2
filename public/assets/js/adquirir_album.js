document.addEventListener("DOMContentLoaded", async () => {
    const urlParams = new URLSearchParams(window.location.search);
    const albumId = urlParams.get('id');

    if (!albumId) return;

    try {
        const response = await fetch(`index.php?url=obter_detalhes_album&id=${albumId}`);
        const album = await response.json();

        if (album.error) {
            console.error(album.error);
            return;
        }

        // AJUSTE DOS IDs PARA BATER COM O SEU HTML:
        document.getElementById('edicaoTitulo').value = album.titulo || '';
        document.getElementById('edicaoCapaUrl').value = album.capa_url || '';
        document.getElementById('edicaoImg').src = album.capa_url || '';
        
        // Para os selects (Artista e Gravadora)
        if (album.artista_id) document.getElementById('edicaoArtista').value = album.artista_id;
        if (album.gravadora_id) document.getElementById('edicaoGravadora').value = album.gravadora_id;

        // Para a data de lançamento
        const campoData = document.querySelector('input[name="data_lancamento"]');
        if (campoData) campoData.value = album.data_lancamento || '';

    } catch (error) {
        console.error("Erro ao processar a batata quente:", error);
    }

    // 1. Escuta o clique no botão de sincronia
    document.getElementById('btn-import-tracks').addEventListener('click', async () => {
        const catalogo = document.getElementById('inputCatalogo').value;
    
        if (!catalogo) {
            alert("Digite o número de catálogo primeiro!");
            return;
        }
    
        // Feedback visual rápido
        const btn = document.getElementById('btn-import-tracks');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Buscando...';
    
        try {
            // 2. Chama a sua rota de importação que já existe no index.php
            const response = await fetch(`index.php?url=api_importar_discogs&query=${catalogo}`);
            const dados = await response.json();
        
            if (dados.error) {
                alert("Erro: " + dados.error);
            } else {
                // 3. Preenche o ID do Discogs e chama a função de renderizar faixas
                document.getElementById('inputDiscogsId').value = dados.id || '';
                
                // Se você já tem a função 'renderizarListaFaixas' no seu JS, chama ela aqui:
                if (typeof renderizarListaFaixas === "function") {
                    renderizarListaFaixas(dados.tracklist);
                }
                
                alert("Faixas importadas com sucesso!");
            }
        } catch (error) {
            console.error("Erro na importação:", error);
        } finally {
            btn.innerHTML = '<i class="fas fa-sync"></i> Importar';
        }
    });
});