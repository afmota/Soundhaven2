<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoundHaven - Relatórios</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" type="image/x-icon" href="/public/assets/images/SoundHaven.ico">
    
    <style>
        .relatorios-container {
            max-width: 800px;
            margin: 30px auto;
            padding: 0 15px;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
        .filter-section {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 6px var(--card-shadow);
        }
        
        .section-title {
            font-size: 1.1rem;
            font-weight: bold;
            color: #fff;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 8px;
        }
        
        .section-title i {
            color: var(--accent-color);
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        .radio-group, .checkbox-group {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 12px;
        }
        
        .radio-item, .checkbox-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-primary);
            cursor: pointer;
            font-size: 0.9rem;
        }
        
        .radio-item input, .checkbox-item input {
            accent-color: var(--accent-color);
            cursor: pointer;
            width: 16px;
            height: 16px;
        }
        
        .form-control {
            width: 100%;
            background-color: var(--bg-color);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-primary);
            padding: 10px 12px;
            font-size: 0.9rem;
            outline: none;
            transition: border-color 0.2s;
        }
        
        .form-control:focus {
            border-color: var(--accent-color);
        }
        
        .scrollable-list {
            max-height: 150px;
            overflow-y: auto;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 10px;
            background-color: var(--bg-color);
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 8px;
        }
        
        /* Personalização da barra de rolagem */
        .scrollable-list::-webkit-scrollbar {
            width: 6px;
        }
        .scrollable-list::-webkit-scrollbar-track {
            background: var(--bg-color);
        }
        .scrollable-list::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: 3px;
        }
        
        .date-range {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        @media (max-width: 600px) {
            .date-range {
                grid-template-columns: 1fr;
            }
        }
        
        .btn-submit {
            background: linear-gradient(to right, #8b5cf6, #ec4899);
            color: #fff;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(139, 92, 246, 0.4);
        }
        
        /* Switch/Toggle customizado */
        .switch-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 26px;
        }
        
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: var(--border-color);
            transition: .4s;
            border-radius: 34px;
        }
        
        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        
        input:checked + .slider {
            background-color: var(--action-positive);
        }
        
        input:checked + .slider:before {
            transform: translateX(24px);
        }
    </style>
</head>
<body>
    <?php require_once __DIR__ . '/../partials/header.php'; ?>

    <div class="relatorios-container">
        <div style="margin-bottom: 25px; display: flex; align-items: center; gap: 15px;">
            <div style="background: linear-gradient(135deg, var(--accent-color), #8b5cf6); width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(212, 0, 255, 0.3);">
                <i class="fas fa-file-pdf" style="font-size: 1.5rem; color: #fff;"></i>
            </div>
            <div>
                <h1 style="font-size: 1.8rem; font-weight: 700; color: #fff; margin: 0;">Relatórios da Coleção</h1>
                <p style="color: var(--text-secondary); margin: 2px 0 0 0; font-size: 0.95rem;">Configure e emita o catálogo da sua coleção em PDF</p>
            </div>
        </div>

        <form action="index.php?url=gerar_relatorio" method="POST" target="_blank" class="form-grid">
            
            <!-- SEÇÃO DE ARTISTAS -->
            <div class="filter-section">
                <div class="section-title">
                    <i class="fas fa-microphone-lines"></i>
                    <span>Filtro de Artistas</span>
                </div>
                
                <div class="form-group">
                    <div class="radio-group">
                        <label class="radio-item">
                            <input type="radio" name="artistas_tipo" value="todos" checked onclick="toggleArtistas('todos')">
                            Todos os Artistas
                        </label>
                        <label class="radio-item">
                            <input type="radio" name="artistas_tipo" value="especifico" onclick="toggleArtistas('especifico')">
                            Artista Específico
                        </label>
                        <label class="radio-item">
                            <input type="radio" name="artistas_tipo" value="multiplos" onclick="toggleArtistas('multiplos')">
                            Múltiplos Artistas
                        </label>
                    </div>
                </div>
                
                <!-- Artista Específico (Dropdown) -->
                <div id="container-artista-especifico" class="form-group" style="display: none;">
                    <label for="artista_id">Selecione o Artista</label>
                    <select name="artista_id" id="artista_id" class="form-control">
                        <option value="">-- Selecione --</option>
                        <?php foreach ($artistas as $art): ?>
                            <option value="<?= $art['artista_id'] ?>"><?= htmlspecialchars($art['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <!-- Múltiplos Artistas (Checkboxes) -->
                <div id="container-artistas-multiplos" class="form-group" style="display: none;">
                    <label>Selecione os Artistas</label>
                    <div class="scrollable-list">
                        <?php foreach ($artistas as $art): ?>
                            <label class="checkbox-item">
                                <input type="checkbox" name="artista_ids[]" value="<?= $art['artista_id'] ?>">
                                <?= htmlspecialchars($art['nome']) ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
            <!-- INTERVALO TEMPORAL -->
            <div class="filter-section">
                <div class="section-title">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Intervalo Temporal</span>
                </div>
                
                <div class="form-group">
                    <label for="tipo_data">Considerar data de:</label>
                    <select name="tipo_data" id="tipo_data" class="form-control">
                        <option value="lancamento">Data de Lançamento (Álbum)</option>
                        <option value="aquisicao">Data de Aquisição (Compra da Mídia)</option>
                    </select>
                </div>
                
                <div class="date-range">
                    <div class="form-group">
                        <label for="data_inicio">Data Inicial</label>
                        <input type="date" name="data_inicio" id="data_inicio" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="data_fim">Data Final</label>
                        <input type="date" name="data_fim" id="data_fim" class="form-control">
                    </div>
                </div>
            </div>
            
            <!-- METADADOS DA MÍDIA -->
            <div class="filter-section">
                <div class="section-title">
                    <i class="fas fa-tags"></i>
                    <span>Metadados da Mídia</span>
                </div>
                
                <!-- Gêneros e Estilos -->
                <div class="form-group">
                    <label>Gêneros e Estilos</label>
                    <div class="scrollable-list">
                        <?php foreach ($generosEstilos as $ge): ?>
                            <label class="checkbox-item">
                                <input type="checkbox" name="generos[]" value="<?= htmlspecialchars($ge) ?>">
                                <?= htmlspecialchars($ge) ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Formatos -->
                <div class="form-group">
                    <label>Formatos</label>
                    <div class="scrollable-list" style="max-height: 100px;">
                        <?php foreach ($formatos as $form): ?>
                            <label class="checkbox-item">
                                <input type="checkbox" name="formatos[]" value="<?= $form['formato_id'] ?>">
                                <?= htmlspecialchars($form['descricao']) ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Gravadoras -->
                <div class="form-group">
                    <label>Gravadoras</label>
                    <div class="scrollable-list">
                        <?php foreach ($gravadoras as $grav): ?>
                            <label class="checkbox-item">
                                <input type="checkbox" name="gravadoras[]" value="<?= $grav['gravadora_id'] ?>">
                                <?= htmlspecialchars($grav['nome']) ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
            <!-- ORDENAÇÃO E CAPA -->
            <div class="filter-section">
                <div class="section-title">
                    <i class="fas fa-sliders"></i>
                    <span>Configurações do Relatório</span>
                </div>
                
                <div class="date-range">
                    <div class="form-group">
                        <label for="ordem">Ordenar por</label>
                        <select name="ordem" id="ordem" class="form-control">
                            <option value="artista">Artista</option>
                            <option value="album">Álbum (Título)</option>
                            <option value="lancamento">Ano de Lançamento</option>
                            <option value="compra">Data de Aquisição</option>
                            <option value="gravadora">Gravadora</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="direcao">Direção</label>
                        <select name="direcao" id="direcao" class="form-control">
                            <option value="ASC">Crescente (A-Z / Antigos)</option>
                            <option value="DESC">Decrescente (Z-A / Recentes)</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group switch-container" style="margin-top: 10px;">
                    <span style="font-size: 0.95rem; color: var(--text-primary);">Incluir Capa dos Álbuns na Tabela</span>
                    <label class="switch">
                        <input type="checkbox" name="incluir_capa" value="1" checked>
                        <span class="slider"></span>
                    </label>
                </div>
            </div>
            
            <!-- BOTÃO DE ENVIO -->
            <button type="submit" class="btn-submit">
                <i class="fas fa-file-pdf"></i>
                Gerar Relatório PDF
            </button>
            
        </form>
    </div>

    <?php require_once __DIR__ . '/../partials/footer.php'; ?>

    <script>
        function toggleArtistas(tipo) {
            const containerEspecifico = document.getElementById('container-artista-especifico');
            const containerMultiplos = document.getElementById('container-artistas-multiplos');
            
            if (tipo === 'todos') {
                containerEspecifico.style.display = 'none';
                containerMultiplos.style.display = 'none';
            } else if (tipo === 'especifico') {
                containerEspecifico.style.display = 'block';
                containerMultiplos.style.display = 'none';
            } else if (tipo === 'multiplos') {
                containerEspecifico.style.display = 'none';
                containerMultiplos.style.display = 'block';
            }
        }
    </script>
</body>
</html>
