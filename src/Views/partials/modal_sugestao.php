<?php if (!empty($sugestaoDoDia)): ?>
<div id="sugestaoModal" class="modal" style="display: block; z-index: 9999;">
    <div class="modal-content modal-detalhes-loja">
        <span class="modal-close" onclick="fecharSugestao()">&times;</span>
        
        <div class="modal-layout-grid">
            <div class="modal-capa-container">
                <img src="<?= htmlspecialchars($sugestaoDoDia['capa_url'] ?: 'assets/images/placeholder.jpg') ?>" alt="Capa">
            </div>

            <div class="modal-info-container">
                <h2 style="color: #3c3cff; margin-top: 0; font-size: 1.2em; letter-spacing: 1px;"><i class="fa-solid fa-star"></i> SUGESTÃO DO DIA</h2>
                <h1 style="margin: 10px 0; font-size: 1.8em;"><?= htmlspecialchars($sugestaoDoDia['titulo']) ?></h1>
                
                <div class="info-data-grid">
                    <p><label>ARTISTA</label><span><?= htmlspecialchars($sugestaoDoDia['artista_nome'] ?: 'N/D') ?></span></p>
                    <p><label>GRAVADORA</label><span><?= htmlspecialchars($sugestaoDoDia['gravadora_nome'] ?: 'N/D') ?></span></p>
                    <p><label>LANÇAMENTO</label><span><?= $sugestaoDoDia['data_lancamento'] ? date('d/m/Y', strtotime($sugestaoDoDia['data_lancamento'])) : 'N/D' ?></span></p>
                    <p><label>TIPO</label><span><?= htmlspecialchars($sugestaoDoDia['tipo_descricao'] ?: 'N/D') ?></span></p>
                    <p><label>SITUAÇÃO</label><span><?= htmlspecialchars($sugestaoDoDia['situacao_descricao'] ?: 'N/D') ?></span></p>
                </div>
                
                <div class="modal-actions" style="margin-top: 30px;">
                    <button type="button" class="btn" style="background-color: #3c3cff; min-width: 150px;" onclick="fecharSugestao()">
                        <i class="fa-solid fa-play"></i> Ouvir Agora
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function fecharSugestao() {
    document.getElementById('sugestaoModal').style.display = 'none';
}
</script>
<?php endif; ?>