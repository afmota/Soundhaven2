<div id="modalGraficoArtistas" class="modal-dashboard">
    <div class="modal-content-dashboard" style="max-width: 800px;">
        <div class="modal-header">
            <h3><i class="fas fa-chart-bar"></i> Distribuição de álbuns por artista</h3>
            <span class="close-modal-artistas">&times;</span>
        </div>
        <div class="modal-body-chart">
            <div style="margin-bottom: 15px; display: flex; align-items: center; gap: 10px;">
                <label>Exibir top:</label>
                <select id="selectLimitArtistas">
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
                <button id="btnAtualizarGrafico" class="btn btn-primary">Atualizar</button>
            </div>
            <div style="height: 400px;">
                <canvas id="chartModalArtistas"></canvas>
            </div>
        </div>
    </div>
</div>