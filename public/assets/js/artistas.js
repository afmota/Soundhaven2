document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modalDetalhesArtista');
    const closeBtn = document.getElementById('closeModalArtista');

    document.querySelectorAll('.js-open-artista-modal').forEach(card => {
        card.addEventListener('click', function() {
            try {
                const artista = JSON.parse(this.dataset.artista);
                
                const txtNome = document.getElementById('detalheNomeArtista');
                const imgFoto = document.getElementById('detalheArtistaFoto');
                const bandeira = document.getElementById('detalheBandeira');
                const nomePais = document.getElementById('nomePaisTexto');
                const txtInicio = document.getElementById('detalheDataInicio');
                const txtGenero = document.getElementById('detalheGeneroPrincipal');
                const txtBio = document.getElementById('detalheBiografia');
                const btnVerAlbuns = document.getElementById('btnVerAlbunsArtista');

                if (txtNome) txtNome.textContent = artista.nome;
                if (imgFoto) imgFoto.src = artista.imagem_url || 'assets/images/placeholder_artist.jpg';

                if (nomePais) {
                    nomePais.textContent = artista.pais_nome || 'Origem não informada';
                }

                if (bandeira) {
                    // Reseta as classes
                    bandeira.className = 'fi'; 
                    
                    if (artista.codigo_iso) {
                        const codigo = artista.codigo_iso.toLowerCase();
                        
                        // Se for Inglaterra, Escócia ou Gales, a biblioteca exige a classe 'fis' (flag-icon-square)
                        if (['gb-eng', 'gb-sct', 'gb-wls'].includes(codigo)) {
                            bandeira.classList.add('fis');
                        }
                        
                        bandeira.classList.add('fi-' + codigo);
                    }
                }

                if (txtInicio) {
                    txtInicio.textContent = artista.data_inicio ? artista.data_inicio.substring(0, 4) : 'N/D';
                }

                if (txtGenero) {
                    txtGenero.textContent = artista.genero_nome || 'N/D';
                }

                if (txtBio) {
                    txtBio.textContent = artista.biografia || 'Nenhuma biografia disponível.';
                }

                if (btnVerAlbuns) {
                    btnVerAlbuns.onclick = function() {
                        window.location.href = `?url=colecao&artista_id=${artista.artista_id}`;
                    };
                }

                if (modal) modal.style.display = 'block';

            } catch (e) {
                console.error("Erro ao abrir modal:", e);
            }
        });
    });

    if (closeBtn) {
        closeBtn.onclick = () => modal.style.display = 'none';
    }
    
    window.onclick = (event) => {
        if (event.target == modal) modal.style.display = 'none';
    };
});