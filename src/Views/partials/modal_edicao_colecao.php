<div class="modal fade" id="modalEdicaoColecao" tabindex="-1" aria-labelledby="modalEdicaoColecaoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark text-white border-primary" style="border-radius: 15px;">
            <div class="modal-header border-secondary">
                <h5 class="modal-title" id="modalEdicaoColecaoLabel">
                    <i class="bi bi-pencil-square text-primary me-2"></i>Editar Álbum (Coleção)
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="formEdicaoColecao">
                    <input type="hidden" id="colecao-edit-id" name="id">
                    
                    <div class="row mb-4 justify-content-center align-items-center">
                        <div class="col-md-4 text-center">
                            <label class="text-muted small text-uppercase d-block mb-2" style="font-size: 0.65rem;">Preview da Capa</label>
                            <img id="colecao-edit-preview-capa" src="" alt="Preview" class="img-fluid rounded shadow border border-secondary" style="max-height: 180px; width: 180px; object-fit: cover;">
                        </div>
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="colecao-edit-capa-url" class="form-label text-muted small text-uppercase" style="font-size: 0.65rem;">URL da Capa</label>
                                <input type="text" class="form-control form-control-sm bg-dark text-white border-secondary shadow-none" id="colecao-edit-capa-url" name="capa_url" placeholder="https://exemplo.com/imagem.jpg">
                            </div>
                            <div class="mb-0">
                                <label for="colecao-edit-titulo" class="form-label text-muted small text-uppercase" style="font-size: 0.65rem;">Título do Álbum</label>
                                <input type="text" class="form-control form-control-sm bg-dark text-white border-secondary shadow-none" id="colecao-edit-titulo" name="titulo">
                            </div>
                        </div>
                    </div>

                    <hr class="border-secondary opacity-25">
                    
                    <div class="row g-3 mb-3">
                        <div class="col-md-8">
                            <label for="colecao-edit-artista" class="form-label text-muted small text-uppercase" style="font-size: 0.65rem;">Artista</label>
                            <div class="input-group">
                                <select class="form-select form-select-sm bg-dark text-white border-secondary shadow-none" id="colecao-edit-artista" name="artista_id">
                                    <option value="">Selecione um Artista</option>
                                    <?php if(isset($listaArtistas)): foreach ($listaArtistas as $artista): ?>
                                        <option value="<?= $artista['id'] ?>"><?= htmlspecialchars($artista['nome']) ?></option>
                                    <?php endforeach; endif; ?>
                                </select>
                                <button class="btn btn-outline-primary btn-sm" type="button" onclick="adicionarArtistaRapido()" title="Cadastrar Novo Artista">
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="colecao-edit-data" class="form-label text-muted small text-uppercase" style="font-size: 0.65rem;">Data de Lançamento</label>
                            <input type="date" class="form-control form-control-sm bg-dark text-white border-secondary shadow-none" id="colecao-edit-data" name="data_lancamento">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="colecao-edit-gravadora" class="form-label text-muted small text-uppercase" style="font-size: 0.65rem;">Gravadora</label>
                            <div class="input-group">
                                <select class="form-select form-select-sm bg-dark text-white border-secondary shadow-none" id="colecao-edit-gravadora" name="gravadora_id">
                                    <option value="">Selecione a Gravadora</option>
                                    <?php if(isset($listaGravadoras)): foreach ($listaGravadoras as $grav): ?>
                                        <option value="<?= $grav['id'] ?>"><?= htmlspecialchars($grav['nome']) ?></option>
                                    <?php endforeach; endif; ?>
                                </select>
                                <button class="btn btn-outline-primary btn-sm" type="button" onclick="adicionarGravadoraRapida()" title="Cadastrar Nova Gravadora">
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="colecao-edit-catalogo" class="form-label text-muted small text-uppercase" style="font-size: 0.65rem;">Número de Catálogo</label>
                            <input type="text" class="form-control form-control-sm bg-dark text-white border-secondary shadow-none" id="colecao-edit-catalogo" name="numero_catalogo" placeholder="Ex: 12345-6">
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="colecao-edit-formato" class="form-label text-muted small text-uppercase" style="font-size: 0.65rem;">Formato</label>
                            <select class="form-select form-select-sm bg-dark text-white border-secondary shadow-none" id="colecao-edit-formato" name="formato_id">
                                <option value="">Selecione</option>
                                <?php if(isset($listaFormatos)): foreach ($listaFormatos as $form): ?>
                                    <option value="<?= $form['id'] ?>"><?= htmlspecialchars($form['descricao']) ?></option>
                                <?php endforeach; endif; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="colecao-edit-tipo" class="form-label text-muted small text-uppercase" style="font-size: 0.65rem;">Tipo de Álbum</label>
                            <select class="form-select form-select-sm bg-dark text-white border-secondary shadow-none" id="colecao-edit-tipo" name="tipo_id">
                                <option value="">Selecione</option>
                                <?php if(isset($listaTipos)): foreach ($listaTipos as $tipo): ?>
                                    <option value="<?= $tipo['id'] ?>"><?= htmlspecialchars($tipo['descricao']) ?></option>
                                <?php endforeach; endif; ?>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-target="#modalColecao" data-bs-toggle="modal">Voltar aos Detalhes</button>
                <button type="submit" form="formEdicaoColecao" class="btn btn-primary btn-sm fw-bold px-4">Salvar Alterações</button>
            </div>
        </div>
    </div>
</div>