<div id="modalEdicaoArtista" class="modal">
    <div class="modal-content">
        <span class="modal-close" id="closeModalEdicaoArtista">&times;</span>
        <h2 id="editArtistaModalTitle" style="color:var(--accent-color); margin-bottom: 20px;">Editar Artista</h2>
        
        <form method="POST" action="?url=salvar_edicao_artista">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="artista_id" id="editArtistaId">
            
            <div class="edit-modal-header-row" style="display: flex; gap: 20px; align-items: flex-end; margin-bottom: 20px;">
                <img id="editArtistaImgPreview" class="edit-modal-capa" src="assets/images/placeholder_artist.jpg" alt="Preview" style="width: 200px; height: 200px; object-fit: cover; border-radius: 6px;">
                <div class="edit-field-group" style="flex: 1;">
                    <label>URL DA IMAGEM</label>
                    <input type="text" name="imagem_url" id="editArtistaImgUrl" placeholder="Cole a URL da foto aqui...">
                </div>
            </div>

            <hr style="margin-bottom: 20px; border: 0; border-top: 1px solid var(--border-color);">

            <div class="edit-field-group">
                <label>NOME DO ARTISTA</label>
                <input type="text" name="nome" id="editArtistaNome" required>
            </div>

            <div class="edit-modal-row">
                <div class="edit-field-group">
                    <label>PAÍS DE ORIGEM</label>
                    <select name="pais_origem" id="editArtistaPais">
                        <option value="">Selecione...</option>
                        <?php foreach ($paises as $p): ?>
                            <option value="<?= (int)$p['pais_id'] ?>"><?= htmlspecialchars($p['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="edit-field-group">
                    <label>GÊNERO PRINCIPAL</label>
                    <select name="genero_principal" id="editArtistaGenero">
                        <option value="">Selecione...</option>
                        <?php foreach ($generos as $g): ?>
                            <option value="<?= (int)$g['genero_id'] ?>"><?= htmlspecialchars($g['descricao'] ?? '') ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="edit-modal-row">
                <div class="edit-field-group">
                    <label>ANO FORMAÇÃO</label>
                    <input type="number" name="ano_formacao" id="editArtistaAnoFormacao" placeholder="YYYY">
                </div>
                <div class="edit-field-group">
                    <label>ANO ENCERRAMENTO</label>
                    <input type="number" name="ano_encerramento" id="editArtistaAnoEncerramento" placeholder="YYYY">
                </div>
            </div>

            <div class="edit-field-group">
                <label>BIOGRAFIA</label>
                <textarea name="biografia" id="editArtistaBio" class="obs-text"rows="4"></textarea>
            </div>

            <div class="edit-field-group">
                <label>SITE OFICIAL</label>
                <input type="text" name="site_oficial" id="editArtistaSite">
            </div>
            
            <div class="modal-actions" style="margin-top:30px; display:flex; justify-content: flex-end; gap:10px;">
                <button type="button" class="btn" style="background-color: #ff3838;" id="btnCancelarEdicaoArtista">Cancelar</button>
                <button type="submit" class="btn" style="background-color: #338d33;"><i class="fa-solid fa-save"></i> Salvar</button>
            </div>
        </form>
    </div>
</div>