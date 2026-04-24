<div id="modalDetalhesArtista" class="modal">
    <div class="modal-content artista-theme">
        <span class="modal-close" id="closeModalArtista">&times;</span>
        
        <div class="modal-body-grid">
            <div class="modal-capa">
                <img id="detalheArtistaFoto" src="" alt="Foto do Artista">
            </div>

            <div class="modal-info-tecnica">
                <h2 id="detalheNomeArtista"></h2>
                <h3 id="detalhePaisArtista">
                    <span id="detalheBandeira" class="fi"></span> 
                    <span id="nomePaisTexto"></span>
                </h3>
                
                <div class="info-row-group">
                    <div class="info-item">
                        <label>Início da Carreira</label>
                        <span id="detalheDataInicio"></span>
                    </div>
                    <div class="info-item">
                        <label>Gênero Principal</label>
                        <span id="detalheGeneroPrincipal" class="highlight-azul"></span>
                    </div>
                </div>

                <div class="info-item full-width mt-15">
                    <label>Biografia / Notas</label>
                    <div id="detalheBiografiaContainer">
                        <p id="detalheBiografia" style="white-space: pre-wrap; margin: 0;"></p>
                    </div>
                </div>

                <div class="info-item full-width mt-15" id="containerSiteOficial" style="display: block;">
                    <label>Site Oficial</label>
                    <div style="display: flex; gap: 10px; align-items: center; margin-top: 5px;">
                        <span id="detalheSiteLink" style="flex: 1; word-break: break-all; color: var(--accent-color);"></span>
                        <a id="btnIrParaSite" href="#" target="_blank" class="btn" style="background-color: #3c3cff; color: white; text-decoration: none; padding: 5px 15px; border-radius: 4px; font-size: 0.9em;">
                            Acessar Site
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-actions">
            <button id="btnVerAlbunsArtista" class="btn btn-primary">Ver Álbuns na Coleção</button>
            <button id="btnEditarArtista" class="btn btn-edit">Editar Cadastro</button>
        </div>
    </div>
</div>