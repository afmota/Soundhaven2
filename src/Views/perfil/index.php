<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoundHaven - Meu Perfil</title>
   
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link rel="icon" type="image/x-icon" href="/public/assets/images/SoundHaven.ico">
    <style>
        .profile-container {
            max-width: 60vw;
            margin: 30px auto;
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 30px;
        }
        @media (max-width: 1024px) {
            .profile-container {
                grid-template-columns: 1fr;
                max-width: 90vw;
            }
        }
        .profile-sidebar {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            background: linear-gradient(to bottom right, var(--card-bg), var(--bg-color));
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 30px 20px;
            box-shadow: 0 4px 6px var(--card-shadow);
        }
        .profile-avatar-large {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 3px solid var(--accent-color);
            margin-bottom: 20px;
            object-fit: cover;
            box-shadow: 0 0 15px var(--accent-color);
        }
        .profile-name {
            font-size: 1.4rem;
            font-weight: 700;
            color: #fff;
            margin: 5px 0;
        }
        .profile-cargo {
            font-size: 0.85rem;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
        }
        .profile-bio {
            font-size: 0.9rem;
            color: var(--text-secondary);
            line-height: 1.5;
            margin: 15px 0;
            border-top: 1px solid var(--border-color);
            padding-top: 15px;
        }
        .profile-details-list {
            width: 100%;
            text-align: left;
            font-size: 0.85rem;
            color: var(--text-secondary);
            list-style: none;
            padding: 0;
            margin: 15px 0 0 0;
            border-top: 1px solid var(--border-color);
            padding-top: 15px;
        }
        .profile-details-list li {
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .profile-details-list li i {
            color: var(--accent-color);
            width: 16px;
        }
        .profile-main-content {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }
        .profile-section-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .profile-section-title i {
            color: var(--cor-destaque);
        }
        .genre-progress-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .genre-progress-item {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 12px 15px;
        }
        .genre-progress-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }
        .genre-progress-bar-bg {
            background: var(--bg-color);
            height: 8px;
            border-radius: 4px;
            overflow: hidden;
            width: 100%;
        }
        .genre-progress-bar-fill {
            height: 100%;
            border-radius: 4px;
        }
        .shortcut-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        .shortcut-button {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            background: linear-gradient(to bottom right, var(--card-bg), var(--bg-color));
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: #fff;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .shortcut-button:hover {
            transform: translateY(-3px);
            border-color: var(--accent-color);
            box-shadow: 0 5px 15px rgba(212, 0, 255, 0.2);
        }
        .shortcut-button i {
            font-size: 1.5rem;
            color: var(--accent-color);
        }
        .shortcut-text h4 {
            margin: 0 0 2px 0;
            font-size: 0.95rem;
            font-weight: 600;
        }
        .shortcut-text p {
            margin: 0;
            font-size: 0.75rem;
            color: var(--text-secondary);
        }
    </style>
</head>
<body>
    <?php require_once __DIR__ . '/../partials/header.php'; ?>

    <div class="page-wrapper">
        <div class="profile-container">
            <!-- Sidebar do Perfil -->
            <aside class="profile-sidebar">
                <img src="assets/images/default-avatar.png" alt="Avatar de <?= htmlspecialchars($nome) ?>" class="profile-avatar-large">
                <h2 class="profile-name"><?= htmlspecialchars($nome) ?></h2>
                <span class="profile-cargo"><?= htmlspecialchars($cargo) ?></span>
                
                <p class="profile-bio"><?= htmlspecialchars($bio) ?></p>
                
                <ul class="profile-details-list">
                    <li><i class="fas fa-envelope"></i> <?= htmlspecialchars($email) ?></li>
                    <li><i class="fas fa-calendar-check"></i> Membro desde Junho, 2026</li>
                    <li><i class="fas fa-shield-alt"></i> Nível: Curador Master</li>
                </ul>
            </aside>

            <!-- Conteúdo Principal do Perfil -->
            <main class="profile-main-content">
                <!-- Painel de Estatísticas Individuais -->
                <div class="card">
                    <h3 class="profile-section-title"><i class="fas fa-chart-line"></i> Estatísticas Gerais do Acervo</h3>
                    <div class="metric-grid" style="margin-bottom: 0;">
                        <div class="card metric-card" style="height: 75px;">
                            <div class="metric-card-content">
                                <div>
                                    <div class="metric-value" style="font-size: 1.5em;"><?= $total_albuns ?></div>
                                    <div class="metric-label" style="font-size: 0.6em;">Total Álbuns</div>
                                </div>
                                <div class="icon-container cor-1" style="width: 35px; height: 35px;"><i class="fas fa-compact-disc" style="font-size: 1em;"></i></div>
                            </div>
                        </div>
                        <div class="card metric-card" style="height: 75px;">
                            <div class="metric-card-content">
                                <div>
                                    <div class="metric-value" style="font-size: 1.5em;"><?= $total_artistas ?></div>
                                    <div class="metric-label" style="font-size: 0.6em;">Artistas</div>
                                </div>
                                <div class="icon-container cor-2" style="width: 35px; height: 35px;"><i class="fas fa-users" style="font-size: 1em;"></i></div>
                            </div>
                        </div>
                        <div class="card metric-card" style="height: 75px;">
                            <div class="metric-card-content">
                                <div>
                                    <div class="metric-value" style="font-size: 1.5em;"><?= $total_lps ?></div>
                                    <div class="metric-label" style="font-size: 0.6em;">LPs de Vinil</div>
                                </div>
                                <div class="icon-container cor-3" style="width: 35px; height: 35px;"><i class="fas fa-record-vinyl" style="font-size: 1em;"></i></div>
                            </div>
                        </div>
                        <div class="card metric-card" style="height: 75px;">
                            <div class="metric-card-content">
                                <div>
                                    <div class="metric-value" style="font-size: 1.5em;"><?= $total_cds ?></div>
                                    <div class="metric-label" style="font-size: 0.6em;">Compact Discs</div>
                                </div>
                                <div class="icon-container cor-4" style="width: 35px; height: 35px;"><i class="fas fa-circle-dot" style="font-size: 1em;"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Painel de Preferência Musical -->
                <div class="card">
                    <h3 class="profile-section-title"><i class="fas fa-heart"></i> Gêneros mais Frequentes no Acervo</h3>
                    <div class="genre-progress-list">
                        <?php if (empty($top_generos)): ?>
                            <p style="color: var(--text-secondary); font-size: 0.9rem;">Nenhum gênero catalogado no acervo.</p>
                        <?php else: ?>
                            <?php 
                            $maxTotal = max(array_column($top_generos, 'total')) ?: 1;
                            $cores = [
                                'linear-gradient(to right, #8b5cf6, #ec4899)',
                                'linear-gradient(to right, #f59e0b, #f97316)',
                                'linear-gradient(to right, #3b82f6, #06b6d4)'
                            ];
                            foreach ($top_generos as $idx => $genero): 
                                $porcentagem = round(($genero['total'] / $maxTotal) * 100);
                                $cor = $cores[$idx] ?? $cores[0];
                            ?>
                                <div class="genre-progress-item">
                                    <div class="genre-progress-header">
                                        <span style="font-weight: 600; color: #fff;"><?= htmlspecialchars($genero['genero']) ?></span>
                                        <span style="color: var(--cor-destaque); font-weight: bold;"><?= $genero['total'] ?> mídias</span>
                                    </div>
                                    <div class="genre-progress-bar-bg">
                                        <div class="genre-progress-bar-fill" style="width: <?= $porcentagem ?>%; background: <?= $cor ?>;"></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Painel de Atalhos Rápidos -->
                <div class="card">
                    <h3 class="profile-section-title"><i class="fas fa-link"></i> Ações Rápidas do Administrador</h3>
                    <div class="shortcut-grid">
                        <a href="?url=dashboard" class="shortcut-button">
                            <i class="fas fa-home"></i>
                            <div class="shortcut-text">
                                <h4>Dashboard</h4>
                                <p>Ir ao painel estatístico</p>
                            </div>
                        </a>
                        <a href="?url=colecao" class="shortcut-button">
                            <i class="fas fa-list-alt"></i>
                            <div class="shortcut-text">
                                <h4>Minha Coleção</h4>
                                <p>Explorar mídias catalogadas</p>
                            </div>
                        </a>
                        <a href="?url=configuracao" class="shortcut-button">
                            <i class="fas fa-tools"></i>
                            <div class="shortcut-text">
                                <h4>Manutenção</h4>
                                <p>Fazer backup e restauração</p>
                            </div>
                        </a>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <?php require_once __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
