<h1><?= htmlspecialchars($tituloPagina) ?></h1>

<p>
    <a href="/albuns/novo">➕ Novo Álbum</a>
</p>

<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>ID</th>
            <th>Capa</th>
            <th>Título</th>
            <th>Artista</th>
            <th>Preço</th>
            <th>Situação</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($albuns)): ?>
            <tr>
                <td colspan="7">Nenhum álbum encontrado.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($albuns as $album): ?>
                <tr>
                    <td><?= (int) $album['id'] ?></td>
                    <td>
                        <?php if (!empty($album['capa_url'])): ?>
                            <img src="<?= htmlspecialchars($album['capa_url']) ?>" alt="Capa"
                                 style="width:50px;height:auto;">
                        <?php else: ?>
                            —
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($album['titulo']) ?></td>
                    <td><?= $album['artista_id'] ?? '—' ?></td>
                    <td>
                        <?= $album['preco_sugerido'] !== null
                            ? 'R$ ' . number_format($album['preco_sugerido'], 2, ',', '.')
                            : '—' ?>
                    </td>
                    <td><?= $album['situacao'] ?? '—' ?></td>
                    <td>
                        <a href="/albuns/editar/<?= (int) $album['id'] ?>">✏️ Editar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
