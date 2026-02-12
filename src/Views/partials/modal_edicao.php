<div class="modal fade" id="modalEdicao" tabindex="-1" aria-labelledby="modalEdicaoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark text-white border-warning" style="border-radius: 15px;">
            <div class="modal-header border-secondary">
                <h5 class="modal-title" id="modalEdicaoLabel">
                    <i class="bi bi-pencil-square text-warning me-2"></i>Editar Álbum
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="formEdicaoAlbum">
                    <input type="hidden" id="edit-id" name="id">
                    
                    <div class="row mb-4 justify-content-center align-items-center">
                        <div class="col-md-4 text-center">
                            <label class="text-muted small text-uppercase d-block mb-2" style="font-size: 0.65rem;">Preview da Capa</label>
                            <img id="edit-preview-capa" src="" alt="Preview" class="img-fluid rounded shadow border border-secondary" style="max-height: 180px; width: 180px; object-fit: cover;">
                        </div>
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="edit-capa-url" class="form-label text-muted small text-uppercase" style="font-size: 0.65rem;">URL da Capa</label>
                                <input type="text" class="form-control form-control-sm bg-dark text-white border-secondary shadow-none" id="edit-capa-url" name="capa_url" placeholder="https://exemplo.com/imagem.jpg">
                            </div>
                        </div>
                    </div>

                    <hr class="border-secondary opacity-25">
                    
                    <div id="container-campos-edicao">
                        <div class="mb-3">
                            <label for="edit-titulo" class="form-label text-muted small text-uppercase" style="font-size: 0.65rem;">Título do Álbum</label>
                            <input type="text" class="form-control form-control-sm bg-dark text-white border-secondary shadow-none" id="edit-titulo" name="titulo">
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-7">
                                <label for="edit-artista" class="form-label text-muted small text-uppercase" style="font-size: 0.65rem;">Artista</label>
                                <select class="form-select form-select-sm bg-dark text-white border-secondary shadow-none" id="edit-artista" name="artista_id">
                                    <option value="">Selecione um Artista</option>
                                    <?php foreach ($listaArtistas as $artista): ?>
                                        <option value="<?= $artista['id'] ?>">
                                            <?= htmlspecialchars($artista['nome']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label for="edit-data" class="form-label text-muted small text-uppercase" style="font-size: 0.65rem;">Data de Lançamento</label>
                                <input type="date" class="form-control form-control-sm bg-dark text-white border-secondary shadow-none" id="edit-data" name="data_lancamento">
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="edit-tipo" class="form-label text-muted small text-uppercase" style="font-size: 0.65rem;">Tipo de Álbum</label>
                                <select class="form-select form-select-sm bg-dark text-white border-secondary shadow-none" id="edit-tipo" name="tipo_id">
                                    <option value="1">Estúdio</option>
                                    <option value="2">EP</option>
                                    <option value="3">Ao Vivo</option>
                                    <option value="4">Compilação</option>
                                    <option value="5">Trilha Sonora</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="edit-situacao" class="form-label text-muted small text-uppercase" style="font-size: 0.65rem;">Situação</label>
                                <select class="form-select form-select-sm bg-dark text-white border-secondary shadow-none" id="edit-situacao" name="situacao">
                                    <option value="1">Disponível</option>
                                    <option value="2">Selecionado</option>
                                    <option value="3">Baixado</option>
                                    <option value="4">Adquirido</option>
                                    <option value="5">Descartado</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-target="#albumModal" data-bs-toggle="modal">Voltar aos Detalhes</button>
                <button type="submit" form="formEdicaoAlbum" class="btn btn-warning btn-sm fw-bold px-4">Salvar Alterações</button>
            </div>
        </div>
    </div>
</div>