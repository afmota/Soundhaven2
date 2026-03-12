<?php 
// O header.php geralmente já inicia a sessão e traz os estilos CSS
require_once 'views/partials/header.php'; 
?>

<main class="container-edicao">
    <section class="top-bar">
        <a href="index.php?url=colecao" class="btn-voltar">← Voltar para a Coleção</a>
        <h1>Editar Álbum</h1>
    </section>

    <form id="formEdicaoAlbum" method="POST" action="index.php?url=salvar_edicao">
        <input type="hidden" name="midia_id" value="<?= $album['midia_id'] ?>">

        <div class="edicao-grid">
            <div class="edicao-col-capa">
                <div class="capa-preview">
                    <img id="imgPreviewCapa" src="<?= $album['capa_url'] ?: 'assets/images/placeholder.jpg' ?>" alt="Capa">
                </div>
                <div class="form-group">
                    <label for="inputCapaUrl">URL da Capa</label>
                    <input type="text" name="capa_url" id="inputCapaUrl" class="form-control" 
                           value="<?= htmlspecialchars($album['capa_url']) ?>">
                </div>
            </div>

            <div class="edicao-col-dados">
                <div class="form-group">
                    <label>Título do Álbum</label>
                    <input type="text" name="titulo" class="form-control" value="<?= htmlspecialchars($album['titulo']) ?>">
                </div>

                <div class="form-group">
                    <label>Artista</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($album['artista_nome']) ?>" readonly title="Artista não editável por aqui">
                </div>

                <div class="form-group">
                    <label>Gravadora</label>
                    <div class="input-group">
                        <select name="gravadora_id" id="selectGravadora" class="form-control">
                            <?php foreach ($gravadoras as $g): ?>
                                <option value="<?= $g['id'] ?>" <?= $g['id'] == $album['gravadora_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($g['nome']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="button" id="btnNovaGravadora" class="btn-add" style="background-color: #3c3cff;">+</button>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <label>Data de Lançamento</label>
                        <input type="date" name="data_lancamento" class="form-control" value="<?= $album['data_lancamento'] ?>">
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-save" style="background-color: #338d33;">Salvar Alterações</button>
            <a href="index.php?url=colecao" class="btn-cancel" style="background-color: #ff3838;">Cancelar</a>
        </div>
    </form>
</main>

<div id="dialogoGravadora" class="modal-overlay" style="display:none;">
    <div class="modal-content-mini">
        <h3>Nova Gravadora</h3>
        <input type="text" id="nomeNovaGravadora" class="form-control" placeholder="Digite o nome...">
        <div class="modal-actions">
            <button type="button" id="btnSalvarGravadora" style="background-color: #338d33;">Salvar</button>
            <button type="button" id="btnCancelarGravadora" style="background-color: #ff3838;">Cancelar</button>
        </div>
    </div>
</div>

<script src="js/edicao.js"></script>

<?php require_once 'views/partials/footer.php'; ?>