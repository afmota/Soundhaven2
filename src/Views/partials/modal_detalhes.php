<div id="albumModal" class="modal">
    <div class="modal-content">
        <span class="modal-close" onclick="closeModal()">&times;</span>
        <div style="flex: 0 0 300px;"><img id="modalImg" style="width:100%; border-radius:4px;" src="" alt="Capa"></div>
        <div style="flex: 1;">
            <h2 id="modalTitle" style="color:var(--accent-color); margin-top:0;"></h2>
            <p><span style="color:var(--text-secondary); font-size:0.8em; display:block;">ARTISTA</span><span id="modalArtist"></span></p>
            <p><span style="color:var(--text-secondary); font-size:0.8em; display:block;">GRAVADORA</span><span id="modalLabel"></span></p>
            <p><span style="color:var(--text-secondary); font-size:0.8em; display:block;">LANÇAMENTO</span><span id="modalDate"></span></p>
            <p><span style="color:var(--text-secondary); font-size:0.8em; display:block;">TIPO</span><span id="modalType"></span></p>
            <p><span style="color:var(--text-secondary); font-size:0.8em; display:block;">SITUAÇÃO</span><span id="modalStatus"></span></p>
            
            <div class="modal-actions" style="margin-top:20px; display:flex; gap:10px;">
                <button class="btn btn-acquire" style="background-color: var(--action-neutral);"><i class="fa-solid fa-cart-shopping"></i> Adquirir</button>
                <button id="btnOpenEdit" class="btn btn-edit" style="background-color: var(--action-positive);"><i class="fa-solid fa-pen"></i> Editar</button>
                <form method="POST" id="formDelete" onsubmit="return confirm('Deseja realmente descartar este álbum?')">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" id="deleteId">
                    <button type="submit" class="btn btn-delete" style="background-color: var(--action-destructive);"><i class="fa-solid fa-trash"></i> Descartar</button>
                </form>
            </div>
        </div>
    </div>
</div>