<div class="modal fade" id="modalImportarCSV" tabindex="-1" aria-labelledby="modalImportarCSVLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-dark text-light border border-info">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-info fw-bold" id="modalImportarCSVLabel">
                    <i class="bi bi-file-earmark-arrow-up me-2"></i>IMPORTAÇÃO EM LOTE (CSV)
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formImportarCSV" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label for="csv_file" class="form-label small text-uppercase fw-bold text-muted">Selecione o arquivo .CSV</label>
                        <input class="form-control bg-dark text-white border-secondary shadow-none" type="file" id="csv_file" name="csv_file" accept=".csv" required>
                        <div class="form-text text-info small">
                            <i class="bi bi-info-circle me-1"></i> O arquivo deve estar codificado em UTF-8.
                        </div>
                    </div>

                    <div class="alert alert-secondary bg-black bg-opacity-25 border-secondary py-3">
                        <h6 class="alert-heading fw-bold small text-uppercase"><i class="bi bi-table me-2"></i>Estrutura Esperada do Cabeçalho:</h6>
                        <code class="text-info d-block mb-3 p-2 bg-dark rounded" style="font-size: 0.85rem;">
                            titulo;capa_url;artista_id;data_lancamento;tipo_id;situacao
                        </code>
                        
                        <div class="row g-3 small">
                            <div class="col-md-6">
                                <span class="fw-bold text-muted text-uppercase d-block mb-1" style="font-size: 0.7rem;">Tipos (tipo_id):</span>
                                <ul class="list-unstyled mb-0" style="font-size: 0.75rem;">
                                    <li>1 - Estúdio | 2 - EP | 3 - Ao Vivo</li>
                                    <li>4 - Compilação | 5 - Trilha Sonora</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <span class="fw-bold text-muted text-uppercase d-block mb-1" style="font-size: 0.7rem;">Situações (situacao):</span>
                                <ul class="list-unstyled mb-0" style="font-size: 0.75rem;">
                                    <li>1 - Disponível | 2 - Selecionado</li>
                                    <li>3 - Baixado | 4 - Adquirido</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </form>
                
                <div id="import-log" class="mt-3 p-2 border border-secondary rounded bg-black d-none" style="max-height: 150px; overflow-y: auto; font-family: monospace; font-size: 0.8rem;">
                </div>
            </div>
            <div class="modal-footer border-secondary bg-black bg-opacity-25">
                <button type="button" class="btn btn-outline-secondary btn-sm fw-bold text-uppercase" data-bs-dismiss="modal">
                    Cancelar
                </button>
                <button type="submit" form="formImportarCSV" id="btnProcessarCSV" class="btn btn-info btn-sm fw-bold text-uppercase text-dark">
                    <i class="bi bi-gear-wide-connected me-2"></i>Processar Importação
                </button>
            </div>
        </div>
    </div>
</div>