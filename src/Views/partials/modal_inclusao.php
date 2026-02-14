<div class="modal fade" id="modalInclusao" tabindex="-1" aria-labelledby="modalInclusaoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-dark text-light border border-success">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-success fw-bold" id="modalInclusaoLabel">
                    <i class="bi bi-plus-circle me-2"></i>CADASTRAR NOVO ÁLBUM
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formInclusaoAlbum">
                    <div class="row">
                        <div class="col-md-4 text-center border-end border-secondary">
                            <label class="d-block small text-uppercase fw-bold mb-2 text-muted">Preview da Capa</label>
                            <img id="inc-preview-capa" 
                                 src="https://placehold.co/300x300?text=Capa+do+Álbum" 
                                 class="img-fluid rounded shadow-sm mb-3 border border-secondary" 
                                 alt="Preview">
                        </div>

                        <div class="col-md-8">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="inc-capa-url" class="form-label small text-uppercase fw-bold text-muted">URL da Capa</label>
                                    <input type="url" class="form-control form-control-sm bg-dark text-white border-secondary shadow-none" 
                                           id="inc-capa-url" name="capa_url" placeholder="https://exemplo.com/imagem.jpg">
                                </div>

                                <div class="col-12">
                                    <label for="inc-titulo" class="form-label small text-uppercase fw-bold text-muted">Título do Álbum</label>
                                    <input type="text" class="form-control form-control-sm bg-dark text-white border-secondary shadow-none" 
                                           id="inc-titulo" name="titulo" required>
                                </div>

                                <div class="col-md-7">
                                    <label for="inc-artista" class="form-label small text-uppercase fw-bold text-muted">Artista</label>
                                    <select class="form-select form-select-sm bg-dark text-white border-secondary shadow-none" 
                                            id="inc-artista" name="artista_id" required>
                                        <option value="" disabled selected>Selecione um artista...</option>
                                        <?php foreach ($listaArtistas as $artista): ?>
                                            <option value="<?= $artista['id'] ?>"><?= htmlspecialchars($artista['nome']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-5">
                                    <label for="inc-data" class="form-label small text-uppercase fw-bold text-muted">Lançamento</label>
                                    <input type="date" class="form-control form-control-sm bg-dark text-white border-secondary shadow-none" 
                                           id="inc-data" name="data_lancamento" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="inc-tipo" class="form-label small text-uppercase fw-bold text-muted">Tipo</label>
                                    <select class="form-select form-select-sm bg-dark text-white border-secondary shadow-none" 
                                            id="inc-tipo" name="tipo_id" required>
                                        <option value="1">Estúdio</option>
                                        <option value="2">EP</option>
                                        <option value="3">Ao Vivo</option>
                                        <option value="4">Compilação</option>
                                        <option value="5">Trilha Sonora</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="inc-situacao" class="form-label small text-uppercase fw-bold text-muted">Situação</label>
                                    <select class="form-select form-select-sm bg-dark text-white border-secondary shadow-none" 
                                            id="inc-situacao" name="situacao" required>
                                        <option value="1">Disponível</option>
                                        <option value="2">Selecionado</option>
                                        <option value="3">Baixado</option>
                                        <option value="4">Adquirido</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-secondary bg-black bg-opacity-25">
                <button type="button" class="btn btn-outline-secondary btn-sm fw-bold text-uppercase" data-bs-dismiss="modal">
                    Cancelar
                </button>
                <button type="submit" form="formInclusaoAlbum" class="btn btn-success btn-sm fw-bold text-uppercase">
                    <i class="bi bi-save me-2"></i>Salvar Álbum
                </button>
            </div>
        </div>
    </div>
</div>