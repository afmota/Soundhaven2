<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoundHaven - Gerenciar Usuários</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" type="image/x-icon" href="/public/assets/images/SoundHaven.ico">
    <style>
        .users-page {
            max-width: 1000px;
            margin: 30px auto;
            padding: 0 20px;
        }
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 24px;
        }
        .page-header h1 {
            color: #fff;
            font-size: 1.8rem;
            margin: 0;
        }
        .alert {
            background-color: rgba(30, 41, 59, 0.9);
            border: 1px solid var(--border-color);
            border-left: 4px solid var(--accent-color);
            padding: 16px;
            margin-bottom: 24px;
            color: #fff;
        }
        .users-table {
            width: 100%;
            border-collapse: collapse;
            background: rgba(15, 23, 42, 0.95);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            overflow: hidden;
        }
        .users-table th,
        .users-table td {
            padding: 16px;
            text-align: left;
            border-bottom: 1px solid rgba(148, 163, 184, 0.12);
        }
        .users-table th {
            background: rgba(30, 41, 59, 0.98);
            color: #f8fafc;
            font-weight: 700;
        }
        .users-table tr:last-child td {
            border-bottom: none;
        }
        .role-badge {
            display: inline-flex;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 700;
            color: #fff;
        }
        .role-user { background: #2563eb; }
        .role-curador { background: #8b5cf6; }
        .btn-promote {
            background: linear-gradient(135deg, #8b5cf6, #ec4899);
            border: none;
            color: white;
            padding: 10px 16px;
            border-radius: 10px;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-promote:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(139, 92, 246, 0.25);
        }
        .disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <?php require_once __DIR__ . '/../partials/header.php'; ?>
    <div class="users-page">
        <div class="page-header">
            <div>
                <h1>Gerenciar Usuários</h1>
                <p style="color: var(--text-secondary); margin: 6px 0 0;">Somente o curador pode promover usuários à função de curador.</p>
            </div>
        </div>

        <?php if (!empty($erro)): ?>
            <div class="alert" style="border-left-color: #f97316;">
                <?php if ($erro === 'invalid'): ?>ID de usuário inválido.
                <?php elseif ($erro === 'notfound'): ?>Usuário não encontrado.
                <?php elseif ($erro === 'already'): ?>Este usuário já é curador.
                <?php else: ?>Falha ao promover o usuário. Tente novamente.
                <?php endif; ?>
            </div>
        <?php elseif (!empty($msg)): ?>
            <div class="alert" style="border-left-color: #10b981;">
                Usuário promovido com sucesso.
            </div>
        <?php endif; ?>

        <table class="users-table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Papel</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?= htmlspecialchars($usuario['nome']) ?></td>
                        <td><?= htmlspecialchars($usuario['email']) ?></td>
                        <td>
                            <span class="role-badge <?= $usuario['role'] === 'curador' ? 'role-curador' : 'role-user' ?>">
                                <?= $usuario['role'] === 'curador' ? 'Curador' : 'Usuário' ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($usuario['role'] === 'curador'): ?>
                                <button class="btn-promote disabled" disabled>Já é curador</button>
                            <?php else: ?>
                                <form action="index.php?url=promover_usuario" method="POST" style="margin:0;">
                                    <input type="hidden" name="usuario_id" value="<?= (int)$usuario['usuario_id'] ?>">
                                    <button type="submit" class="btn-promote">Promover</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
