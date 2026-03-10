<div id="modalDetalhesColecao" class="modal">
    <div class="modal-content colecao-theme">
        <span class="close-modal">&times;</span>
        
        <div class="modal-body-grid">
            <div class="modal-capa">
                <img id="detalheCapa" src="" alt="Capa do Álbum">
                <span id="detalheFormatoTag" class="format-tag-modal"></span>
            </div>

            <div class="modal-info-tecnica">
                <h2 id="detalheTitulo"></h2>
                <h3 id="detalheArtista"></h3>
                
                <div class="info-row-group">
                    <div class="info-item">
                        <label>Lançamento</label>
                        <span id="detalheLancamento"></span>
                    </div>
                    <div class="info-item">
                        <label>Aquisição</label>
                        <span id="detalheAquisicao" class="highlight-verde"></span>
                    </div>
                </div>

                <div class="info-row-group">
                    <div class="info-item">
                        <label>Gravadora</label>
                        <span id="detalheGravadora"></span>
                    </div>
                    <div class="info-item">
                        <label>Nº Catálogo</label>
                        <span id="detalheCatalogo"></span>
                    </div>
                </div>

                <div class="info-item">
                    <label>Preço Pago</label>
                    <span id="detalhePreco"></span>
                </div>
            </div>
        </div> <hr class="modal-divider">

        <div class="modal-metadata-section">
            
            <div class="genres-styles-row">
                <div class="info-item">
                    <label>Gêneros</label>
                    <div id="containerTagsGeneros" class="tag-cloud"></div>
                </div>
                <div class="info-item">
                    <label>Estilos</label>
                    <div id="containerTagsEstilos" class="tag-cloud"></div>
                </div>
            </div>

            <div class="info-item full-width mt-15">
                <label>Produção</label>
                <div id="containerTagsProdutores" class="tag-cloud"></div>
            </div>

            <div class="info-item full-width mt-15">
                <label>Observações</label>
                <p id="detalheObservacoes" class="obs-text"></p>
            </div>
        </div> <hr class="modal-divider">

        <div id="containerFaixas" class="tracklist-section">
            <div class="tracklist-header">
                <label>Faixas do Álbum</label>
                <button type="button" id="btnAdicionarFaixa" class="btn-add-track">+ Add Faixa</button>
            </div>
            
            <table class="track-table">
                <thead>
                    <tr>
                        <th style="width: 40px;">#</th>
                        <th>Título</th>
                        <th style="width: 100px;">Duração</th>
                        <th style="width: 80px;">Ações</th>
                    </tr>
                </thead>
                <tbody id="corpoTabelaFaixas">
                    </tbody>
            </table>
        </div>
    </div>
</div>