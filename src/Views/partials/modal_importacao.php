<div id="importModal" class="modal">
    <div class="modal-content" style="max-width: 500px;">
        <span class="modal-close" onclick="document.getElementById('importModal').style.display='none'">&times;</span>
        <h2 style="color: var(--accent-color);">Importar Lote (CSV)</h2>
        <form action="?url=loja" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="import_csv">
            
            <div class="edit-field-group" style="margin-top: 20px;">
                <label>SELECIONE O ARQUIVO CSV</label>
                <input type="file" name="csv_file" accept=".csv" required>
            </div>
            
            <p style="font-size: 0.8em; color: #888; margin-top: 10px;">
                Formato esperado: titulo, capa_url, artista_id, gravadora_id, data_lancamento, tipo_id, situacao
            </p>

            <button type="submit" class="btn" style="background-color: #338d33; width: 100%; margin-top: 20px;">
                <i class="fas fa-upload"></i> PROCESSAR IMPORTAÇÃO
            </button>
        </form>
    </div>
</div>