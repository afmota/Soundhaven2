<div class="modal fade" id="albumModal" tabindex="-1" aria-labelledby="albumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark text-white border-secondary" style="border-radius: 15px;">
            <div class="modal-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-4 text-center">
                        <img id="modal-capa" src="" alt="Capa" class="img-fluid rounded shadow-lg border border-secondary" style="max-height: 250px; object-fit: cover;">
                    </div>

                    <div class="col-md-8 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start">
                            <h4 id="modal-titulo" class="fw-bold mb-0 text-white"></h4>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <h6 id="modal-artista" class="text-success mt-1 mb-0 opacity-75"></h6>
                        
                        <hr class="my-3 border-secondary">

                        <div class="row g-3">
                            <div class="col-6">
                                <span class="text-muted text-uppercase d-block" style="font-size: 0.65rem; letter-spacing: 1px;">Lançamento</span>
                                <span id="modal-ano" class="fw-bold" style="font-size: 0.9rem;"></span>
                            </div>
                            <div class="col-6">
                                <span class="text-muted text-uppercase d-block" style="font-size: 0.65rem; letter-spacing: 1px;">Tipo</span>
                                <span id="modal-tipo" class="fw-bold" style="font-size: 0.9rem;"></span>
                            </div>
                            <div class="col-6">
                                <span class="text-muted text-uppercase d-block" style="font-size: 0.65rem; letter-spacing: 1px;">Situação</span>
                                <span id="modal-situacao" class="fw-bold" style="font-size: 0.9rem;"></span>
                            </div>
                            <div class="col-6">
                                <span class="text-muted text-uppercase d-block" style="font-size: 0.65rem; letter-spacing: 1px;">Inclusão</span>
                                <span id="modal-inclusao" class="fw-bold" style="font-size: 0.9rem;"></span>
                            </div>
                        </div>

                        <hr class="my-3 border-secondary">

                        <div class="d-flex gap-2 justify-content-end">
                            <button class="btn btn-outline-light btn-sm px-3">
                                <i class="bi bi-pencil-square me-1"></i> Editar
                            </button>
                            <button class="btn btn-outline-danger btn-sm px-3">
                                <i class="bi bi-trash me-1"></i> Descartar
                            </button>
                            <button class="btn btn-success btn-sm px-3 fw-bold">
                                <i class="bi bi-cart-plus me-1"></i> Adquirir
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>