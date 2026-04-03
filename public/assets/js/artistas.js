console.log("Script de artistas carregado com sucesso!");
alert("JS de artistas funcionando!");

document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modalDetalhesArtista');
    const closeBtn = document.getElementById('closeModalArtista');

    document.querySelectorAll('.js-open-artista-modal').forEach(card => {
        card.addEventListener('click', function() {
            try {
                const artista = JSON.parse(this.dataset.artista);
                console.log("Dados do artista clicado:", artista);

                // --- Mapeamento dos Elementos do Modal ---
                const txtNome = document.getElementById('detalheNomeArtista');
                const imgFoto = document.getElementById('detalheArtistaFoto');
                const bandeira = document.getElementById('detalheBandeira');
                const nomePais = document.getElementById('nomePaisTexto');
                const txtInicio = document.getElementById('detalheDataInicio');
                const txtGenero = document.getElementById('detalheGeneroPrincipal');
                const txtBio = document.getElementById('detalheBiografia');
                const btnVerAlbuns = document.getElementById('btnVerAlbunsArtista');

                // 1. Preenchimento de Nome e Foto
                if (txtNome) txtNome.textContent = artista.nome;
                if (imgFoto) imgFoto.src = artista.imagem_url || 'assets/images/placeholder_artist.jpg';

                // 2. Lógica da Bandeira e Nome do País
                if (nomePais) {
                    nomePais.textContent = artista.pais_nome || 'Origem não informada';
                }

                if (bandeira) {
                    bandeira.className = 'fi'; // Reseta as classes para não acumular bandeiras
                    if (artista.codigo_iso) {
                        bandeira.classList.add('fi-' + artista.codigo_iso.toLowerCase());
                    }
                }

                // 3. Informações Adicionais (Início, Gênero e Biografia)
                if (txtInicio) {
                    // Pega apenas o ano da data de início
                    txtInicio.textContent = artista.data_inicio ? artista.data_inicio.substring(0, 4) : 'N/D';
                }

                if (txtGenero) {
                    txtGenero.textContent = artista.genero_nome || 'N/D';
                }

                if (txtBio) {
                    txtBio.textContent = artista.biografia || 'Nenhuma biografia disponível para este artista.';
                }

                // 4. Configuração do Botão "Ver Álbuns na Coleção"
                if (btnVerAlbuns) {
                    btnVerAlbuns.onclick = function() {
                        // Redireciona para a coleção filtrando pelo artista
                        window.location.href = `?url=colecao&artista_id=${artista.artista_id}`;
                    };
                }

                // 5. Exibir o Modal
                if (modal) modal.style.display = 'block';

            } catch (e) {
                console.error("Erro ao processar clique no artista:", e);
            }
        });
    });

    // --- Lógica para Fechar o Modal ---
    if (closeBtn) {
        closeBtn.onclick = () => {
            modal.style.display = 'none';
        };
    }
    
    window.onclick = (event) => {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    };
});