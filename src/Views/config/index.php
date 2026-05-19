<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoundHaven - Dashboard</title>
   
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
<body>
    <?php require_once __DIR__ . '/../partials/header.php'; ?>
    <div class="container">
        <h2><i class="fas fa-database"></i> Gestão da Base de Dados</h2>
        <hr>

        <div class="row">
            <div class="card col-md-5">
                <h3>Gerar Backup</h3>
                <p>Cria um arquivo .sql com todos os álbuns, músicas e estatísticas atuais.</p>
                <a href="index.php?url=configuracao/backup" class="btn btn-save" style="background: #338d33; color: white; padding: 10px; text-decoration: none; display: inline-block; border-radius: 5px;">
                    <i class="fas fa-file-download"></i> Escolher local e Salvar Backup
                </a>
            </div>

            <div class="card col-md-5" style="margin-left: 20px;">
                <h3>Restaurar Base</h3>
                <p style="color: #ff3838;"><strong>Atenção:</strong> Isso substituirá todos os dados atuais!</p>
                <form action="index.php?url=configuracao/restaurar" method="POST" enctype="multipart/form-data">
                    <input type="file" name="backup_file" accept=".sql" required>
                    <br><br>
                    <button type="submit" class="btn" style="background: #ff3838; color: white; border: none; padding: 10px; border-radius: 5px; cursor: pointer;" onclick="return confirm('Tem certeza? Isso apagará os dados atuais!')">
                        <i class="fas fa-upload"></i> Upload e Restaurar
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>