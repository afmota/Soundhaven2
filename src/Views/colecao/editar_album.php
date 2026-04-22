<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoundHaven - Editar Álbum</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/editAlbum.css">
</head>
<body class="colecao-module">
    <?php include __DIR__ . '/../partials/header.php'; ?>

    <div class="content">
        <h2 id="edicaoHeaderTitle">
            Editar: <?= htmlspecialchars($album['titulo'] ?? '') ?>
        </h2>

        <form method="POST" action="index.php?url=salvar_edicao">
            
            <input type="hidden" name="album_id" value="<?= $album['album_id'] ?? '' ?>">
            <input type="hidden" name="midia_id" value="<?= $album['midia_id'] ?? '' ?>">

            <div id="edicaoPaginaBody">
                <div class="edit-modal-header-row">
                    <img id="edicaoImg" class="edit-modal-capa" src="<?= htmlspecialchars($album['capa_url'] ?? '') ?>" alt="Capa Edição">
                    <div class="edit-field-group" style="margin-bottom: 0;">
                        <label>URL DA CAPA</label>
                        <input type="text" name="capa_url" id="edicaoCapaUrl" value="<?= htmlspecialchars($album['capa_url'] ?? '') ?>">
                    </div>
                </div>

                <hr class="edit-modal-separator">

                <div class="edit-field-group">
                    <label>TÍTULO DO ÁLBUM</label>
                    <input type="text" name="titulo" id="edicaoTitulo" value="<?= htmlspecialchars($album['titulo'] ?? '') ?>">
                </div>

                <div class="edit-modal-row">
                    <div class="edit-field-group">
                        <label>ARTISTA</label>
                        <select name="artista_id" id="edicaoArtista">
                            <option value="">Selecione...</option>
                            <?php foreach ($artistas as $art): ?>
                            <option value="<?= $art['artista_id'] ?>" <?= (isset($album['artista_id']) && $art['artista_id'] == $album['artista_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($art['nome']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="edit-field-group">
                        <div class="label-with-action">
                            <label>GRAVADORA</label>
                        </div>
                        
                        <input type="hidden" name="gravadora_id" id="idGravadoraHidden" value="<?= $album['gravadora_id'] ?? '' ?>">

                        <input type="text" 
                            name="gravadora_nome" 
                            id="edicaoGravadora" 
                            class="input-edicao" 
                            list="listaSugestoesGravadoras" 
                            value="<?= htmlspecialchars($album['gravadora_nome'] ?? '') ?>" 
                            placeholder="Busque ou digite uma nova...">
                                                            
                        <datalist id="listaSugestoesGravadoras">
                            <?php foreach ($gravadoras as $grav): ?>
                                <option value="<?= htmlspecialchars($grav['nome']) ?>" data-id="<?= $grav['gravadora_id'] ?>">
                            <?php endforeach; ?>
                        </datalist>
                    </div>
                </div>

                <div class="edit-modal-row">
                    <div class="edit-field-group">
                        <label>DATA DE LANÇAMENTO</label>
                        <input type="date" name="data_lancamento" value="<?= $album['data_lancamento'] ?? '' ?>">
                    </div>
                    <div class="edit-field-group">
                        <label>DATA DE AQUISIÇÃO</label>
                        <input type="date" name="data_aquisicao" value="<?= $album['data_aquisicao'] ?? '' ?>">
                    </div>
                    <div class="edit-field-group">
                        <label>TIPO DE MÍDIA</label>
                        <select name="tipo_id">
                            <?php foreach ($tipos as $tp): ?>
                            <option value="<?= $tp['tipo_id'] ?>" <?= (isset($album['tipo_id']) && $tp['tipo_id'] == $album['tipo_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($tp['descricao']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="edit-field-group">
                    <label>PREÇO DE AQUISIÇÃO (R$)</label>
                    <input type="text" name="preco" 
                           value="<?= number_format($album['preco'] ?? 0, 2, ',', '') ?>" 
                           class="input-edicao" placeholder="0,00">
                    <small style="color: rgba(255,255,255,0.5)">Use vírgula para centavos (ex: 45,90)</small>
                </div>

                <div class="edit-field-group">
                    <div class="label-with-action">
                        <label>Nº de Catálogo</label>
                        <button type="button" id="btn-import-tracks" class="btn-add-tag" title="Importar faixas do Discogs">
                            <i class="fas fa-sync"></i> Importar
                        </button>
                    </div>
                    <input type="text" name="numero_catalogo" id="inputCatalogo" 
                           class="input-edicao" value="<?= htmlspecialchars($album['numero_catalogo'] ?? '') ?>" 
                           placeholder="Ex: 88875120972">
                </div>

                <input type="hidden" name="discogs_id" id="inputDiscogsId" value="<?= $album['discogs_id'] ?? '' ?>">        

                <div class="edit-field-group" style="margin-top: 20px;">
                    <label>OBSERVAÇÕES / HISTÓRIA DO ITEM</label>
                    <textarea name="observacoes" class="input-edicao" rows="4" 
                              style="width: 100%; background-color: rgba(0,0,0,0.2); color: #eee; border: 1px solid rgba(255,255,255,0.2); border-radius: 4px; padding: 10px; resize: vertical;"
                              placeholder="Conte a história deste item na sua coleção..."><?= htmlspecialchars($album['observacoes'] ?? '') ?></textarea>
                </div>

                <hr class="edit-modal-separator">

                <h3 class="edicao-subtitle">Classificação e Produção</h3>
                <div class="edit-modal-row">
                    <div class="edit-field-group">
                        <div class="label-with-action">
                            <label>GÊNEROS</label>
                            <button type="button" class="btn-add-tag" data-target="Generos">
                                <i class="fas fa-plus-circle"></i>
                            </button>
                        </div>
                        <div class="search-tag-container" id="searchContainerGeneros" style="display: none;">
                            <input type="text" class="input-search-tag" placeholder="Buscar ou novo..." data-tipo="generos" list="listaSugestoesGeneros">
                        </div>
                        <div class="tags-container" id="containerGeneros">
                            <?php 
                            $listaGeneros = explode('|', $album['generos'] ?? '');
                            foreach ($listaGeneros as $gen): if(empty(trim($gen))) continue; ?>
                            <span class="tag-item">
                                <?= htmlspecialchars($gen) ?>
                                <input type="hidden" name="generos[]" value="<?= htmlspecialchars($gen) ?>">
                                <i class="fas fa-times remove-tag"></i>
                            </span>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="edit-field-group">
                        <div class="label-with-action">
                            <label>ESTILOS</label>
                            <button type="button" class="btn-add-tag" data-target="Estilos">
                                <i class="fas fa-plus-circle"></i>
                            </button>
                        </div>
                        <div class="search-tag-container" id="searchContainerEstilos" style="display: none;">
                            <input type="text" class="input-search-tag" placeholder="Buscar ou novo..." data-tipo="estilos" list="listaSugestoesEstilos">
                        </div>
                        <div class="tags-container" id="containerEstilos">
                            <?php 
                            $listaEstilos = explode('|', $album['estilos'] ?? '');
                            foreach ($listaEstilos as $est): if(empty(trim($est))) continue; ?>
                            <span class="tag-item">
                                <?= htmlspecialchars($est) ?>
                                <input type="hidden" name="estilos[]" value="<?= htmlspecialchars($est) ?>">
                                <i class="fas fa-times remove-tag"></i>
                            </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="edit-modal-row">
                    <div class="edit-field-group">
                        <div class="label-with-action">
                            <label>PRODUTORES</label>
                            <button type="button" class="btn-add-tag" data-target="Produtores">
                                <i class="fas fa-plus-circle"></i>
                            </button>
                        </div>
                        <div class="search-tag-container" id="searchContainerProdutores" style="display: none;">
                            <input type="text" class="input-search-tag" placeholder="Buscar ou novo..." data-tipo="produtores" list="listaSugestoesProdutores">
                        </div>
                        <div class="tags-container" id="containerProdutores">
                            <?php 
                            $listaProdutores = explode('|', $album['produtores'] ?? '');
                            foreach ($listaProdutores as $prod): if(empty(trim($prod))) continue; ?>
                            <span class="tag-item">
                                <?= htmlspecialchars($prod) ?>
                                <input type="hidden" name="produtores[]" value="<?= htmlspecialchars($prod) ?>">
                                <i class="fas fa-times remove-tag"></i>
                            </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <hr class="edit-modal-separator">

                <div class="edicao-section-faixas">
                    <div class="label-with-action">
                        <h3 class="edicao-subtitle">Faixas / Músicas</h3>
                        <button type="button" class="btn-add-tag" id="btnAdicionarFaixa" title="Adicionar Faixa">
                            <i class="fas fa-plus-circle"></i>
                        </button>
                    </div>
                    <div id="containerListaFaixas" class="lista-faixas-edicao">
                        <div class="faixas-header">
                            <span class="col-pos">#</span>
                            <span class="col-titulo">Título</span>
                            <span class="col-duracao">Duração</span>
                            <span class="col-acoes"></span>
                        </div>
                        <div id="corpoListaFaixas">
                            <?php foreach ($faixas as $index => $faixa): ?>
                            <div class="faixa-item" data-posicao="<?= $faixa['numero_faixa'] ?>">
                                <input type="number" name="faixas[<?= $index ?>][numero_faixa]" 
                                       value="<?= $faixa['numero_faixa'] ?>" class="input-pos">
                                <input type="text" name="faixas[<?= $index ?>][titulo]" 
                                       value="<?= htmlspecialchars($faixa['titulo']) ?>" class="input-titulo">
                                <input type="text" name="faixas[<?= $index ?>][duracao]" 
                                       value="<?= $faixa['duracao'] ?>" class="input-duracao mask-tempo" placeholder="00:00:00">
                                <button type="button" class="btn-remove-faixa"><i class="fas fa-trash"></i></button>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="edicao-actions" style="margin-top:30px; display:flex; justify-content: flex-end; gap:10px;">
                    <button type="submit" class="btn" style="background-color: var(--action-positive);"><i class="fa-solid fa-save"></i> Salvar</button>
                    <button type="button" class="btn" style="background-color: var(--action-destructive);" onclick="window.history.back()">Cancelar</button>
                </div>
            </div>
        </form>
    </div>

    <?php include __DIR__ . '/../partials/footer.php'; ?>

    <datalist id="listaSugestoesGeneros">
        <?php foreach ($sugestoes['generos'] as $gen): ?><option value="<?= htmlspecialchars($gen) ?>"><?php endforeach; ?>
    </datalist>
    <datalist id="listaSugestoesEstilos">
        <?php foreach ($sugestoes['estilos'] as $est): ?><option value="<?= htmlspecialchars($est) ?>"><?php endforeach; ?>
    </datalist>
    <datalist id="listaSugestoesProdutores">
        <?php foreach ($sugestoes['produtores'] as $prod): ?><option value="<?= htmlspecialchars($prod) ?>"><?php endforeach; ?>
    </datalist>

    <script src="assets/js/functions.js"></script>
    <script src="assets/js/edicao_album.js"></script>
</body>
</html>