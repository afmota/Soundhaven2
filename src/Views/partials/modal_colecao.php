<div class="modal fade" id="modalColecao" tabindex="-1" aria-labelledby="modalColecaoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark text-white border-secondary" style="border-radius: 15px; box-shadow: 0 0 30px rgba(0,0,0,0.5);">
            
            <div class="modal-body p-4">
                <div class="row">
                    <div class="col-md-5 text-center border-end border-secondary border-opacity-25">
                        <img id="colecao-modal-capa" src="" alt="Capa" class="img-fluid rounded shadow-lg mb-3 border border-secondary" style="max-height: 300px; width: 100%; object-fit: cover;">
                        
                        <div class="bg-black bg-opacity-25 rounded p-3 text-start">
                            <div class="mb-2">
                                <span class="text-muted text-uppercase d-block" style="font-size: 0.6rem; letter-spacing: 1px;">Formato</span>
                                <span id="colecao-modal-formato" class="fw-bold text-info" style="font-size: 0.85rem;"></span>
                            </div>
                            <div class="mb-2">
                                <span class="text-muted text-uppercase d-block" style="font-size: 0.6rem; letter-spacing: 1px;">Gravadora</span>
                                <span id="colecao-modal-gravadora" class="fw-bold" style="font-size: 0.85rem;"></span>
                            </div>
                            <div>
                                <span class="text-muted text-uppercase d-block" style="font-size: 0.6rem; letter-spacing: 1px;">Adquirido em</span>
                                <span id="colecao-modal-aquisicao" class="fw-bold text-success" style="font-size: 0.85rem;"></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-7 d-flex flex-column ps-md-4">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <div>
                                <h4 id="colecao-modal-titulo" class="fw-bold mb-0 text-white"></h4>
                                <h6 id="colecao-modal-artista" class="text-primary mt-1 fw-bold"></h6>
                            </div>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div id="colecao-modal-tags" class="mb-3">
                            </div>

                        <div class="tracklist-container bg-black bg-opacity-50 rounded p-3 flex-grow-1" style="max-height: 400px; overflow-y: auto;">
                            <h6 class="text-uppercase text-muted mb-3" style="font-size: 0.7rem; border-bottom: 1px solid #333; padding-bottom: 8px;">
                                <i class="bi bi-music-note-list me-2"></i>Faixas
                            </h6>
                            <ul id="colecao-modal-faixas" class="list-unstyled mb-0 small">
                                </ul>
                        </div>
                        
                        <div id="colecao-modal-obs" class="mt-3 small text-muted fst-italic border-start border-primary ps-2" style="display: none;">
                            </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-secondary border-opacity-25 bg-black bg-opacity-25 p-3" style="border-bottom-left-radius: 15px; border-bottom-right-radius: 15px;">
                <div class="container-fluid">
                    <div class="row align-items-center">
                        <div class="col-4 p-0">
                            <button type="button" id="btn-descartar-colecao" class="btn btn-outline-danger btn-sm d-flex align-items-center gap-2">
                                <i class="bi bi-trash3"></i>
                                <span class="d-none d-md-inline">Descartar Item</span>
                            </button>
                        </div>
                        
                        <div class="col-8 p-0 text-end">
                            <button id="btn-editar-colecao" class="btn btn-outline-primary btn-sm px-4 me-2">
                                <i class="bi bi-pencil-square me-1"></i> Editar
                            </button>
                            <button type="button" class="btn btn-secondary btn-sm px-4" data-bs-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>