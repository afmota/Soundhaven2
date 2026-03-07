<div id="createModal" class="modal">
    <div class="modal-content">
        <span class="modal-close" onclick="closeCreateModal()">&times;</span>
        
        <h2 id="createModalHeaderTitle" style="color: var(--accent-color); margin-bottom: 20px;">Adicionar Novo Álbum</h2>

        <form action="?url=loja" method="POST" id="formCreateAlbum">
            <input type="hidden" name="action" value="create">

            <div class="edit-modal-header-row">
                <img src="assets/images/placeholder.jpg" id="createModalImg" class="edit-modal-capa" alt="Preview">
                
                <div style="display: flex; flex-direction: column; gap: 15px; flex-grow: 1;">
                    <div class="edit-field-group">
                        <label>TÍTULO DO ÁLBUM</label>
                        <input type="text" name="titulo" placeholder="Ex: Master of Puppets" required>
                    </div>
                    <div class="edit-field-group">
                        <label>URL DA CAPA</label>
                        <input type="text" name="capa_url" id="createModalCapaUrl" placeholder="https://i.scdn.co/image/...">
                    </div>
                </div>
            </div>

            <hr class="edit-modal-separator">

            <div class="edit-modal-row">
                <div class="edit-field-group">
                    <label>ARTISTA</label>
                    <select name="artista_id" required>
                        <option value="">Selecione um artista...</option>
                        <?php foreach ($artistas as $art): ?>
                            <option value="<?= $art['id'] ?>"><?= htmlspecialchars($art['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="edit-field-group">
                    <label>GRAVADORA</label>
                    <select name="gravadora_id">
                        <option value="">Selecione uma gravadora...</option>
                        <?php foreach ($gravadoras as $grav): ?>
                            <option value="<?= $grav['id'] ?>"><?= htmlspecialchars($grav['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="edit-modal-row">
                <div class="edit-field-group">
                    <label>TIPO</label>
                    <select name="tipo_id" required>
                        <option value="">Selecione...</option>
                        <?php foreach ($tipos as $t): ?>
                            <option value="<?= $t['tipo_id'] ?>"><?= htmlspecialchars($t['descricao']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="edit-field-group">
                    <label>SITUAÇÃO</label>
                    <select name="situacao" required>
                        <option value="">Selecione...</option>
                        <?php foreach ($situacoes as $s): ?>
                            <option value="<?= $s['situacao_id'] ?>"><?= htmlspecialchars($s['descricao']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="edit-field-group">
                    <label>DATA DE LANÇAMENTO</label>
                    <input type="date" name="data_lancamento">
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 15px; margin-top: 30px;">
                <button type="button" class="btn" style="background-color: #ff3838; width: 150px;" onclick="closeCreateModal()">
                    <i class="fas fa-times"></i> CANCELAR
                </button>
                <button type="submit" class="btn" style="background-color: #338d33; width: 180px;">
                    <i class="fas fa-check"></i> CADASTRAR ÁLBUM
                </button>
            </div>
        </form>
    </div>
</div>