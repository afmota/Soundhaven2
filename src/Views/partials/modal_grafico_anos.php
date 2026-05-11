<div id="modalAnos" class="modal-dashboard" style="display:none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.7);">
    <div class="modal-content-dashboard" style="background-color: #1a1a1a; margin: 5% auto; padding: 20px; width: 70%; border-radius: 8px; border: 1px solid #333;">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #333; padding-bottom: 10px;">
            <h3 style="color: #fff;"><i class="fas fa-shopping-cart" style="color: #338d33;"></i> Aquisições por Ano</h3>
            <span class="close-modal" onclick="fecharModal('modalAnos')" style="color: #aaa; font-size: 28px; cursor: pointer;">&times;</span>
        </div>
        <div class="modal-body-chart" style="height: 450px; margin-top: 20px;">
            <canvas id="chartAnos"></canvas>
        </div>
    </div>
</div>