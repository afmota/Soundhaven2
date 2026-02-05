<?php if (!empty($errors)): ?>
    <div style="color: red;">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post"
      action="<?= isset($album['id'])
          ? '/albuns/atualizar/' . (int)$album['id']
          : '/albuns/salvar' ?>">

<h1><?= htmlspecialchars($tituloPagina) ?></h1>

<p>
    <a href="/albuns">⬅ Voltar</a>
</p>

<form method="post" action="">
    <div>
        <label for="titulo">Título *</label><br>
        <input type="text" name="titulo" id="titulo" required
               value="<?= htmlspecialchars($album['titulo'] ?? '') ?>">
    </div>

    <div>
        <label for="capa_url">URL da Capa</label><br>
        <input type="url" name="capa_url" id="capa_url"
               value="<?= htmlspecialchars($album['capa_url'] ?? '') ?>">
    </div>

    <div>
        <label for="data_lancamento">Data de Lançamento</label><br>
        <input type="date" name="data_lancamento" id="data_lancamento"
               value="<?= htmlspecialchars($album['data_lancamento'] ?? '') ?>">
    </div>

    <div>
        <label for="tipo_id">Tipo</label><br>
        <select name="tipo_id" id="tipo_id">
            <option value="">— Selecione —</option>
            <?php foreach ($tipos as $tipo): ?>
                <option value="<?= (int) $tipo['id'] ?>"
                    <?= isset($album['tipo_id']) && $album['tipo_id'] == $tipo['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($tipo['descricao']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div>
        <label for="situacao">Situação</label><br>
        <select name="situacao" id="situacao">
            <option value="">— Selecione —</option>
            <?php foreach ($situacoes as $situacao): ?>
                <option value="<?= (int) $situacao['id'] ?>"
                    <?= isset($album['situacao']) && $album['situacao'] == $situacao['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($situacao['descricao']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div>
        <label for="preco_sugerido">Preço sugerido</label><br>
        <input type="number" step="0.01" name="preco_sugerido" id="preco_sugerido"
               value="<?= htmlspecialchars($album['preco_sugerido'] ?? '') ?>">
    </div>

    <br>

    <button type="submit">💾 Salvar</button>
</form>
