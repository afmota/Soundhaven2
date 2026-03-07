<aside class="sidebar-filters">
    <form method="GET" action="">
        <input type="hidden" name="url" value="loja">
        <h3><i class="fa-solid fa-sliders"></i> Filtros</h3>
        
        <div class="filter-group">
            <label>Título do Álbum</label>
            <input type="text" name="titulo" value="<?= htmlspecialchars($filters['titulo'] ?? '') ?>" placeholder="Ex: Master of Puppets">
        </div>

        <div class="filter-group">
            <label>Artista</label>
            <select name="artista_id">
                <option value="">Todos os Artistas</option>
                <?php foreach ($artistas as $art): ?>
                    <option value="<?= $art['id'] ?>" <?= ($filters['artista_id'] ?? '') == $art['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($art['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="filter-group">
            <label>Tipo</label>
            <select name="tipo_id">
                <option value="">Todos os Tipos</option>
                <?php foreach ($tipos as $t): ?>
                    <option value="<?= $t['tipo_id'] ?>" <?= ($filters['tipo_id'] ?? '') == $t['tipo_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($t['descricao']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="filter-group">
            <label>Situação</label>
            <select name="situacao_id">
                <option value="">Todas as Situações</option>
                <?php foreach ($situacoes as $s): ?>
                    <option value="<?= $s['situacao_id'] ?>" <?= ($filters['situacao_id'] ?? '') == $s['situacao_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($s['descricao']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-search" style="background-color: var(--action-positive); color: #fff;">
            <i class="fa-solid fa-magnifying-glass"></i> Filtrar
        </button>
        <a href="?url=loja" class="btn btn-clear">
            <i class="fa-solid fa-rotate-left"></i> Limpar Filtros
        </a>
    </form>
</aside>