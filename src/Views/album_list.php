<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="dark"> <head>
    <meta charset="UTF-8">
    <title>Soundhaven - Vitrine Dark</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            /* Gradiente suave: do cinza quase preto para um tom levemente azulado/grafite */
            background: radial-gradient(circle at top, #1e2124 0%, #121212 100%);
            background-attachment: fixed;
            color: #e0e0e0;
            min-height: 100vh;
        }

        .vitrine-container {
            max-width: 60%;
            margin: 0 auto;
        }

        .album-card {
            background-color: #1a1a1a;
            /* Bordas bem arredondadas */
            border-radius: 15px; 
            border: 1px solid rgba(255, 255, 255, 0.05);
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            /* Sombra inicial leve */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        .album-card:hover {
            transform: translateY(-10px) scale(1.02);
            /* Sombra de destaque no hover */
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.6) !important;
            border-color: #1db954;
            z-index: 10;
        }

        .album-img-container {
            width: 100%;
            aspect-ratio: 1 / 1;
            overflow: hidden;
            /* O arredondamento superior acompanha o card */
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }

        .album-img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }

        .album-card:hover img {
            transform: scale(1.1);
        }

        .text-primary {
            color: #1db954 !important;
        }

        /* Grid de 5 colunas */
        @media (min-width: 992px) {
            .col-custom-5 { flex: 0 0 auto; width: 20%; }
        }

        .filter-panel {
            position: fixed;
            top: 50px;
            right: 2%; /* Fica na área livre fora dos 60% centrais */
            width: 15%; /* Largura proporcional ao espaço lateral */
            background: rgba(30, 30, 30, 0.95);
            padding: 20px;
            border-radius: 15px;
            border: 1px solid #333;
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }

        .filter-panel label {
            font-size: 0.8rem;
            color: #1db954;
            text-transform: uppercase;
            margin-bottom: 5px;
            display: block;
        }

        .filter-panel input, .filter-panel select {
            background: #121212;
            border: 1px solid #444;
            color: white;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <aside class="filter-panel">
        <h6 class="text-white mb-3 text-center border-bottom pb-2" style="border-color: #1db954 !important;">LOJA FILTROS</h6>
        <form action="index.php" method="GET">
            <label>Título</label>
            <input type="text" name="titulo" class="form-control form-control-sm shadow-none" value="<?= htmlspecialchars($filtros['titulo']) ?>">

            <label>Artista</label>
            <select name="artista" class="form-select form-select-sm shadow-none">
                <option value="">Todos os Artistas</option>
                <?php foreach ($listaArtistas as $artista): ?>
                    <option value="<?= $artista['id'] ?>" <?= ($filtros['artista'] ?? '') == $artista['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($artista['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>    

            <label>Tipo do Álbum</label>
            <select name="tipo" class="form-select form-select-sm shadow-none">
                <option value="">Todos</option>
                <option value="1" <?= $filtros['tipo'] == '1' ? 'selected' : '' ?>>Estúdio</option>
                <option value="2" <?= $filtros['tipo'] == '2' ? 'selected' : '' ?>>EP</option>
                <option value="3" <?= $filtros['tipo'] == '3' ? 'selected' : '' ?>>Ao Vivo</option>
                <option value="4" <?= $filtros['tipo'] == '4' ? 'selected' : '' ?>>Compilação</option>
                <option value="5" <?= $filtros['tipo'] == '5' ? 'selected' : '' ?>>Trilha Sonora</option>
            </select>

            <label>Situação</label>
            <select name="situacao" class="form-select form-select-sm shadow-none">
                <option value="">Padrão (Vitrine)</option>
                <option value="1" <?= $filtros['situacao'] == '1' ? 'selected' : '' ?>>Disponível</option>
                <option value="2" <?= $filtros['situacao'] == '2' ? 'selected' : '' ?>>Selecionado</option>
                <option value="3" <?= $filtros['situacao'] == '3' ? 'selected' : '' ?>>Baixado</option>
                <option value="4" <?= $filtros['situacao'] == '4' ? 'selected' : '' ?>>Adquirido</option>
                <option value="5" <?= $filtros['situacao'] == '5' ? 'selected' : '' ?>>Descartado</option>
            </select>

            <button type="submit" class="btn btn-success btn-sm w-100 mt-3 fw-bold">FILTRAR AGORA</button>
            <a href="index.php" class="btn btn-outline-danger btn-sm w-100 mt-2">LIMPAR</a>
        </form>
    </aside>

    <div class="container-fluid py-5 vitrine-container">
        <header class="text-center mb-5">
            <h1 class="display-5 fw-bold text-white">Soundhaven Shop</h1>
            <p class="text-muted">Explore sua loja de álbuns</p>
        </header>
        
        <div class="row g-4 justify-content-center">
            <?php foreach ($albuns as $album): ?>
                <div class="col-6 col-md-4 col-custom-5">
                    <div class="card h-100 shadow-sm album-card">
                        <div class="album-img-container">
                            <img src="<?= $album->getCapaUrl() ?>" 
                                 alt="<?= $album->getTitulo() ?>"
                                 loading="lazy">
                        </div>
                        <div class="card-body p-3 text-center">
                            <h6 class="card-title text-truncate mb-1" title="<?= $album->getTitulo() ?>">
                                <?= $album->getTitulo() ?>
                            </h6>
                            <p class="text-primary small mb-1">
                                <strong><?= $album->getArtistaNome() ?></strong>
                            </p>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="badge bg-dark text-muted border border-secondary">
                                    <?= $album->getAnoLancamento() ?>
                                </span>
                                <small class="text-muted small">#<?= $album->getArtistaId() ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <nav class="mt-5">
            <ul class="pagination justify-content-center">
                <?php if($paginaAtual > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $paginaAtual - 1 ?>">Anterior</a>
                    </li>
                <?php endif; ?>

                <?php for($i = $pagInicio; $i <= $pagFim; $i++): ?>
                    <li class="page-item <?= $i == $paginaAtual ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <?php if($paginaAtual < $totalPaginas): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $paginaAtual + 1 ?>">Próxima</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
    <div class="modal fade" id="albumModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content bg-dark text-light" style="border: 1px solid #333; border-radius: 15px;">
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-5">
                            <img id="modal-capa" src="" alt="Capa" class="img-fluid rounded shadow-lg w-100">
                        </div>

                        <div class="col-md-7 d-flex flex-column justify-content-center">
                            <button type="button" class="btn-close btn-close-white ms-auto mb-2" data-bs-dismiss="modal"></button>
                            <h2 id="modal-titulo" class="fw-bold mb-0 text-white"></h2>
                            <h4 id="modal-artista" class="text-success mb-4"></h4>

                            <div class="row g-3 text-uppercase fw-light small">
                                <div class="col-6">
                                    <span class="text-muted d-block small">Ano de Lançamento</span>
                                    <strong id="modal-ano" class="text-white fs-6"></strong>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted d-block small">Tipo</span>
                                    <strong id="modal-tipo" class="text-white fs-6"></strong>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted d-block small">Situação</span>
                                    <strong id="modal-situacao" class="text-white fs-6"></strong>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted d-block small">Data de Inclusão</span>
                                    <strong id="modal-inclusao" class="text-white fs-6"></strong>
                                </div>
                            </div>

                            <hr class="my-4" style="border-color: #444;">

                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-light flex-grow-1">
                                    <i class="bi bi-pencil-square"></i> Editar
                                </button>
                                <button class="btn btn-outline-danger flex-grow-1">
                                    <i class="bi bi-trash"></i> Descartar
                                </button>
                                <button class="btn btn-success flex-grow-1 fw-bold">
                                    <i class="bi bi-cart-check"></i> Adquirir
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    function abrirModal(dados) {
        // Mapeamento de nomes de Situação e Tipo (Exemplo baseado na sua estrutura)
        const situacoes = { 1: "Disponível", 2: "Selecionado", 3: "Baixado", 4: "Adquirido", 5: "Descartado" };
        const tipos = { 1: "Estúdio", 2: "EP", 3: "Ao Vivo", 4: "Compilação", 5: "Trilha Sonora" };
                    
        document.getElementById('modal-capa').src = dados.capa || 'caminho/para/imagem_padrao.jpg';
        document.getElementById('modal-titulo').innerText = dados.titulo;
        document.getElementById('modal-artista').innerText = dados.artista;
        document.getElementById('modal-ano').innerText = dados.ano;
        document.getElementById('modal-tipo').innerText = tipos[dados.tipo] || "N/A";
        document.getElementById('modal-situacao').innerText = situacoes[dados.situacao] || "N/A";
        document.getElementById('modal-inclusao').innerText = dados.inclusao;
                    
        // Dispara o modal do Bootstrap
        const meuModal = new bootstrap.Modal(document.getElementById('albumModal'));
        meuModal.show();
    }
</script>
</body>
</html>