<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoundHaven - Novo Artista</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/artistas.css">
</head>
<body>

<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="page-wrapper">
    <div class="spacer-left"></div>
    <div class="main-section">
        <div class="page-actions" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; gap: 10px;">
            <h1 style="color: var(--accent-color);">Novo Artista</h1>
            <a href="?url=artistas" class="btn btn-primary" style="text-decoration: none;">Voltar</a>
        </div>

        <div class="modal" style="display: block; position: relative; background: transparent; box-shadow: none; padding: 0;">
            <div class="modal-content" style="width: 100%; max-width: 900px; margin: 0 auto;">
                <h2 style="color:var(--accent-color); margin-bottom: 20px;">Incluir novo artista</h2>

                <form method="POST" action="?url=salvar_inclusao_artista">
                    <div class="edit-modal-header-row" style="display: flex; gap: 20px; align-items: flex-end; margin-bottom: 20px;">
                        <img id="newArtistaImgPreview" class="edit-modal-capa" src="assets/images/placeholder_artist.jpg" alt="Preview" style="width: 200px; height: 200px; object-fit: cover; border-radius: 6px;">
                        <div class="edit-field-group" style="flex: 1;">
                            <label>URL DA IMAGEM</label>
                            <input type="text" name="imagem_url" id="newArtistaImgUrl" placeholder="Cole a URL da foto aqui...">
                        </div>
                    </div>

                    <hr style="margin-bottom: 20px; border: 0; border-top: 1px solid var(--border-color);">

                    <div class="edit-field-group">
                        <label>NOME DO ARTISTA</label>
                        <input type="text" name="nome" id="newArtistaNome" required>
                    </div>

                    <div class="edit-modal-row">
                        <div class="edit-field-group">
                            <label>PAÍS DE ORIGEM</label>
                            <select name="pais_origem" id="newArtistaPais">
                                <option value="">Selecione...</option>
                                <?php foreach ($paises as $p): ?>
                                    <option value="<?= (int)$p['pais_id'] ?>"><?= htmlspecialchars($p['nome']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="edit-field-group">
                            <label>GÊNERO PRINCIPAL</label>
                            <select name="genero_principal" id="newArtistaGenero">
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
                            <input type="number" name="ano_formacao" id="newArtistaAnoFormacao" placeholder="YYYY">
                        </div>
                        <div class="edit-field-group">
                            <label>ANO ENCERRAMENTO</label>
                            <input type="number" name="ano_encerramento" id="newArtistaAnoEncerramento" placeholder="YYYY">
                        </div>
                    </div>

                    <div class="edit-field-group">
                        <label>BIOGRAFIA</label>
                        <textarea name="biografia" id="newArtistaBio" class="obs-text" rows="4"></textarea>
                    </div>

                    <div class="edit-field-group">
                        <label>SITE OFICIAL</label>
                        <input type="text" name="site_oficial" id="newArtistaSite">
                    </div>

                    <div class="modal-actions" style="margin-top:30px; display:flex; justify-content: flex-end; gap:10px;">
                        <a href="?url=artistas" class="btn" style="background-color: #ff3838; text-decoration: none; color: #fff; display: inline-flex; align-items: center; justify-content: center;">Cancelar</a>
                        <button type="submit" class="btn" style="background-color: #338d33;"><i class="fa-solid fa-save"></i> Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../partials/footer.php'; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputUrl = document.getElementById('newArtistaImgUrl');
        const imgPreview = document.getElementById('newArtistaImgPreview');

        if (inputUrl && imgPreview) {
            inputUrl.addEventListener('input', function() {
                const novaUrl = this.value.trim();
                imgPreview.src = novaUrl || 'assets/images/placeholder_artist.jpg';
            });
        }
    });
</script>
</body>
</html>
