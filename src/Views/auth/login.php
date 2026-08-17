<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoundHaven - Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" type="image/x-icon" href="/public/assets/images/SoundHaven.ico">
    <style>
        body {
            padding: 0;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background-color: var(--bg-color);
            background-image: radial-gradient(circle at 10% 20%, rgba(139, 92, 246, 0.15) 0%, transparent 40%),
                              radial-gradient(circle at 90% 80%, rgba(212, 0, 255, 0.15) 0%, transparent 40%);
        }
        
        .auth-container {
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }
        
        .auth-card {
            background-color: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 40px 30px;
            box-shadow: 0 8px 32px var(--card-shadow);
            text-align: center;
        }
        
        .auth-logo {
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 30px;
            text-decoration: none;
        }
        
        .auth-logo img {
            width: 80px;
            height: auto;
            margin-bottom: 10px;
        }
        
        .auth-logo-text {
            font-size: 1.8rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: 0.5px;
        }
        
        .auth-logo-subtitle {
            font-size: 0.85rem;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-top: 3px;
        }
        
        .auth-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #fff;
            margin-bottom: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        
        .form-group label {
            display: block;
            color: var(--text-secondary);
            font-size: 0.85rem;
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        .input-group {
            position: relative;
            display: flex;
            align-items: center;
        }
        
        .input-group i {
            position: absolute;
            left: 14px;
            color: var(--text-muted);
            font-size: 1rem;
        }
        
        .form-control {
            width: 100%;
            background-color: var(--bg-color);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-primary);
            padding: 12px 14px 12px 42px;
            font-size: 0.95rem;
            outline: none;
            transition: all 0.2s ease;
        }
        
        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(212, 0, 255, 0.15);
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
            width: 100%;
            transition: transform 0.2s, box-shadow 0.2s;
            margin-top: 10px;
            margin-bottom: 20px;
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(139, 92, 246, 0.4);
        }
        
        .auth-footer {
            font-size: 0.85rem;
            color: var(--text-secondary);
        }
        
        .auth-footer a {
            color: var(--accent-color);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }
        
        .auth-footer a:hover {
            color: #ec4899;
            text-decoration: underline;
        }
        
        .alert {
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 20px;
            font-size: 0.85rem;
            text-align: left;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .alert-danger {
            background-color: rgba(239, 68, 68, 0.15);
            border: 1px solid var(--action-destructive);
            color: #fca5a5;
        }
        
        .alert-success {
            background-color: rgba(16, 185, 129, 0.15);
            border: 1px solid var(--action-positive);
            color: #a7f3d0;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-logo">
                <img src="assets/images/SoundHaven.png" alt="Logo SoundHaven">
                <div class="auth-logo-text">SoundHaven</div>
                <div class="auth-logo-subtitle">Acervo Digital</div>
            </div>
            
            <h2 class="auth-title">Acessar Conta</h2>
            
            <?php if (!empty($mensagemErro)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div><?= htmlspecialchars($mensagemErro) ?></div>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($mensagemSucesso)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <div><?= htmlspecialchars($mensagemSucesso) ?></div>
                </div>
            <?php endif; ?>
            
            <form action="index.php?url=processar_login" method="POST">
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <div class="input-group">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" class="form-control" placeholder="seu@email.com" required autocomplete="username">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="senha">Senha</label>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="senha" name="senha" class="form-control" placeholder="Digite sua senha" required autocomplete="current-password">
                    </div>
                </div>
                
                <button type="submit" class="btn-submit">Entrar</button>
            </form>
            
            <div class="auth-footer">
                Não tem uma conta? <a href="index.php?url=cadastro">Cadastre-se</a>
            </div>
        </div>
    </div>
</body>
</html>
