<div id="modalGraficoGravadoras" class="modal-dashboard">
    <div class="modal-content-dashboard" style="max-width: 800px;">
        <div class="modal-header">
            <h3><i class="fas fa-record-vinyl"></i> Distribuição de álbuns por gravadora</h3>
            <span class="close-modal-gravadoras">&times;</span>
        </div>
        <div class="modal-body-chart">
            <div style="margin-bottom: 15px; display: flex; align-items: center; gap: 10px;">
                <label>Exibir top:</label>
                <select id="selectLimitGravadoras">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
                <button id="btnAtualizarGravadoras" class="btn btn-primary">Atualizar</button>
            </div>
            <div style="height: 400px;">
                <canvas id="chartModalGravadoras"></canvas>
            </div>
        </div>
    </div>
</div>