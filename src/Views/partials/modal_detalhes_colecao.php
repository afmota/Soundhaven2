<div id="modalDetalhesColecao" class="modal">
    <div class="modal-content colecao-theme">
        <span class="modal-close">&times;</span>
        
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
        </div>
        
        <hr class="modal-divider">

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
                <div id="detalheObservacoes" class="obs-text"></div>
            </div>
        </div>
        
        <hr class="modal-divider">

        <div id="containerFaixas" class="tracklist-section">
            <div class="tracklist-header">
                <label>Faixas do Álbum</label>
            </div>
            
            <div id="area-tabela-faixas">
                <table class="track-table"> <thead>
                        <tr>
                            <th>#</th>
                            <th>Título</th>
                            <th>Duração</th>
                            <th>Vídeo</th>
                        </tr>
                    </thead>
                    <tbody id="corpoTabelaFaixas">
                        </tbody>
                </table>
            </div>
        </div>

        <div class="modal-actions">
            <button id="btnEditarColecao" class="btn btn-edit">Editar</button>
            <button id="btnDescartarColecao" class="btn btn-delete">Descartar</button>
        </div>
    </div>
</div>

<div id="modalVideoFaixa" class="modal" style="display:none;">
    <div class="modal-content colecao-theme" style="max-width: 720px;">
        <span class="modal-close" data-close-video-modal>&times;</span>
        <div class="modal-body-grid" style="display:block;">
            <h3 style="margin-bottom:12px;">Vídeo da faixa</h3>
            <p id="textoVideoFaixa" style="margin-bottom:12px; color:#aaa;">Insira a URL do YouTube ou Vimeo para associar ao vídeo da música.</p>
            <div id="areaInputVideoFaixa">
                <input id="inputVideoUrlFaixa" type="url" placeholder="https://www.youtube.com/watch?v=..." style="width:100%; padding:10px; border-radius:6px; border:1px solid #444; margin-bottom:12px;">
            </div>
            <div id="conteudoVideoFaixa" style="display:none; margin-bottom:12px;">
                <iframe id="iframeVideoFaixa" width="100%" height="315" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
            <div style="display:flex; justify-content:flex-end; gap:10px; align-items:center;">
                <span id="statusVideoFaixa" style="color:#ffb703; font-size:.9rem; margin-right:auto;"></span>
                <button id="btnSalvarVideoFaixa" class="btn btn-edit" type="button">Salvar vídeo</button>
            </div>
        </div>
    </div>
</div>