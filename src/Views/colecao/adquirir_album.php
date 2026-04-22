<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoundHaven - Adquirir Álbum</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="colecao-module">
    <?php include __DIR__ . '/../partials/header.php'; ?>

    <div class="modal-content">
        <h2 id="edicaoHeaderTitle" style="color:var(--border-light); margin-top:0; margin-bottom: 0px;">
            Adicionar à Coleção: <?= htmlspecialchars($album['titulo'] ?? 'Novo Item') ?>
        </h2>

        <form method="POST" action="index.php?url=salvar_inclusao">
            
            <input type="hidden" name="album_id" value="<?= $album['album_id'] ?? '' ?>">

            <div id="edicaoPaginaBody">
                <div class="edit-modal-header-row" style="margin: 0px;">
                    <img id="edicaoImg" class="edit-modal-capa" src="<?= htmlspecialchars($album['capa_url'] ?? '') ?>" alt="Capa">
                    <div class="edit-field-group" style="margin-bottom: 00px;">
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
                        <label>GRAVADORA</label>
                        
                        <input type="text" 
                            name="gravadora_nome" 
                            id="edicaoGravadoraNome" 
                            class="input-edicao" 
                            list="listaSugestoesGravadoras" 
                            value="<?= htmlspecialchars($album['gravadora_nome'] ?? '') ?>" 
                            placeholder="Busque ou digite...">

                        <input type="hidden" name="gravadora_id" id="edicaoGravadoraId" 
                            value="<?= htmlspecialchars($album['gravadora_id'] ?? '') ?>">
                                                
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
                        <input type="date" name="data_aquisicao" value="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="edit-field-group">
                        <label>FORMATO</label> <select name="formato_id">
                            <option value="">Selecione...</option>
                            <?php foreach ($formatos as $f): ?>
                                <option value="<?= $f['formato_id'] ?>">
                                    <?= htmlspecialchars($f['descricao']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="edit-field-group">
                    <label>PREÇO DE AQUISIÇÃO (R$)</label>
                    <input type="text" name="preco" value="" class="input-edicao" placeholder="0,00">
                    <small style="color: rgba(255,255,255,0.5)">Quanto você pagou por este item?</small>
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
                    <input type="hidden" name="discogs_id" id="inputDiscogsId" value="<?= $album['discogs_id'] ?? '' ?>">
                </div>

                <div class="edit-field-group" style="margin-top: 20px;">
                    <label>OBSERVAÇÕES / HISTÓRIA DO ITEM</label>
                    <textarea name="observacoes" class="input-edicao" rows="4" 
                              style="width: 100%; background-color: rgba(0,0,0,0.2); color: #eee; border: 1px solid rgba(255,255,255,0.2); border-radius: 4px; padding: 10px; resize: vertical;"
                              placeholder="Onde comprou? É uma edição especial?"></textarea>
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
                        <button type="button" class="btn-add-tag" id="btnAdicionarFaixa"><i class="fas fa-plus-circle"></i></button>
                    </div>
                    <div id="containerListaFaixas" class="lista-faixas-edicao">
                        <div class="faixas-header">
                            <span class="col-pos">#</span>
                            <span class="col-titulo">Título</span>
                            <span class="col-duracao">Duração</span>
                            <span class="col-acoes"></span>
                        </div>
                        <div id="corpoListaFaixas">
                            </div>
                    </div>
                </div>

                <div class="edicao-actions" style="margin-top:30px; display:flex; justify-content: flex-end; gap:10px;">
                    <button type="submit" class="btn" style="background-color: var(--action-positive);"><i class="fa-solid fa-cart-plus"></i> Adicionar à Coleção</button>
                    <button type="button" class="btn" style="background-color: var(--action-destructive);" onclick="window.history.back()">Desistir</button>
                </div>
            </div>
        </form>
    </div>

    <datalist id="listaSugestoesGeneros">
        <?php foreach ($sugestoes['generos'] as $g): ?>
            <option value="<?= htmlspecialchars($g) ?>">
        <?php endforeach; ?>
    </datalist>
        
    <datalist id="listaSugestoesEstilos">
        <?php foreach ($sugestoes['estilos'] as $e): ?>
            <option value="<?= htmlspecialchars($e) ?>">
        <?php endforeach; ?>
    </datalist>
        
    <datalist id="listaSugestoesProdutores">
        <?php foreach ($sugestoes['produtores'] as $p): ?>
            <option value="<?= htmlspecialchars($p) ?>">
        <?php endforeach; ?>
    </datalist>

    <script src="assets/js/functions.js"></script>
    <script src="assets/js/adquirir_album.js"></script>
</body>
</html>