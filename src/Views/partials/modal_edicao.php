<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="modal-close" onclick="closeEditModal()">&times;</span>
        <h2 id="editModalHeaderTitle" style="color:var(--accent-color); margin-top:0; margin-bottom: 20px;"></h2>
        
        <form method="POST" action="">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="album_id" id="editModalAlbumId">
            
            <div id="editModalBody">
                <div class="edit-modal-header-row">
                    <img id="editModalImg" class="edit-modal-capa" src="" alt="Capa Edição">
                    <div class="edit-field-group">
                        <label>URL DA CAPA</label>
                        <input type="text" name="capa_url" id="editModalCapaUrl">
                    </div>
                </div>

                <hr class="edit-modal-separator">

                <div class="edit-field-group">
                    <label>TÍTULO DO ÁLBUM</label>
                    <input type="text" name="titulo" id="editModalTitulo">
                </div>

                <div class="edit-modal-row">
                    <div class="edit-field-group">
                        <label>ARTISTA</label>
                        <select name="artista_id" id="editModalArtista"> <option value="">Selecione...</option>
                            <?php foreach ($artistas as $art): ?>
                                <option value="<?= $art['id'] ?>"><?= htmlspecialchars($art['nome']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="edit-field-group">
                        <label>GRAVADORA</label>
                        <select name="gravadora_id" id="editModalGravadora"> <option value="">Selecione...</option>
                            <?php foreach ($gravadoras as $grav): ?>
                                <option value="<?= $grav['id'] ?>"><?= htmlspecialchars($grav['nome']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="edit-modal-row">
                    <div class="edit-field-group">
                        <label>TIPO</label>
                        <select name="tipo_id" id="editModalTipo">
                            <option value="">Selecione...</option>
                            <?php foreach ($tipos as $t): ?>
                                <option value="<?php echo (int)$t['tipo_id']; ?>">
                                    <?php echo htmlspecialchars($t['descricao']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="edit-field-group">
                        <label>SITUAÇÃO</label>
                        <select name="situacao" id="editModalSituacao">
                            <option value="">Selecione...</option>
                            <?php foreach ($situacoes as $s): ?>
                                <option value="<?php echo (int)$s['situacao_id']; ?>">
                                    <?php echo htmlspecialchars($s['descricao']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="edit-field-group" style="margin-top: 15px;">
                    <label>DATA DE LANÇAMENTO</label>
                    <input type="date" name="data_lancamento" id="editModalData">
                </div>
                
                <div class="modal-actions" style="margin-top:30px; display:flex; justify-content: flex-end; gap:10px;">
                    <button type="button" class="btn" style="background-color: var(--action-destructive);" onclick="closeEditModal()">Cancelar</button>
                    <button type="submit" class="btn" style="background-color: var(--action-positive);"><i class="fa-solid fa-save"></i> Salvar</button>
                </div>
            </div>
        </form>
    </div>
</div>