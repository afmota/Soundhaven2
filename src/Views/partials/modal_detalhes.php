<div class="modal fade" id="albumModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark text-light" style="border: 1px solid #333; border-radius: 15px; background: #111827 !important;">
            <div class="modal-body p-4">
                <div class="row">
                    <div class="col-md-5 mb-3 mb-md-0">
                        <img id="modal-capa" src="" alt="Capa" class="img-fluid rounded shadow-lg w-100" style="aspect-ratio: 1/1; object-fit: cover;">
                    </div>
                    
                    <div class="col-md-7 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h2 id="modal-titulo" class="fw-bold text-white mb-0 h3"></h2>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <h4 id="modal-artista" class="text-success mb-4 h5"></h4>
                        
                        <div class="row g-3 text-uppercase fw-light small">
                            <div class="col-6">
                                <span class="text-muted d-block" style="font-size: 0.7rem;">Ano de Lançamento</span>
                                <strong id="modal-ano" class="text-white"></strong>
                            </div>
                            <div class="col-6">
                                <span class="text-muted d-block" style="font-size: 0.7rem;">Tipo</span>
                                <strong id="modal-tipo" class="text-white"></strong>
                            </div>
                            <div class="col-6">
                                <span class="text-muted d-block" style="font-size: 0.7rem;">Situação</span>
                                <strong id="modal-situacao" class="text-white"></strong>
                            </div>
                            <div class="col-6">
                                <span class="text-muted d-block" style="font-size: 0.7rem;">Data de Inclusão</span>
                                <strong id="modal-inclusao" class="text-white"></strong>
                            </div>
                        </div>

                        <hr class="my-4" style="border-color: rgba(255,255,255,0.1);">

                        <div class="mt-auto d-flex gap-2">
                            <button class="btn btn-outline-light btn-sm flex-grow-1 py-2">
                                <i class="bi bi-pencil-square"></i> Editar
                            </button>
                            <button class="btn btn-outline-danger btn-sm flex-grow-1 py-2">
                                <i class="bi bi-trash"></i> Descartar
                            </button>
                            <button class="btn btn-success btn-sm flex-grow-1 py-2 fw-bold">
                                <i class="bi bi-cart-check"></i> Adquirir
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>