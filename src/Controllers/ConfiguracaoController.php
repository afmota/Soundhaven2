<?php
namespace App\Controllers;

class ConfiguracaoController {
    
    private $backupDir;

    public function __construct() {
        $this->backupDir = __DIR__ . '/../../backups/';
    }

    public function index() {
        include __DIR__ . '/../Views/config/index.php';
    }

    public function backup() {
        $host = getenv('DB_HOST');
        $db   = getenv('DB_NAME');
        $user = getenv('DB_USER');
        $pass = getenv('DB_PASS');
    
        $data = date('Y-m-d_H-i-s');
        $filename = "backup_colecao_{$data}.sql";
        
        // Caminho temporário apenas para gerar o arquivo
        $tempPath = __DIR__ . '/../../backups/' . $filename;
    
        if (!is_dir(dirname($tempPath))) mkdir(dirname($tempPath), 0777, true);
    
        // 1. Gera o backup no servidor temporariamente
        $comando = "mysqldump -h {$host} -u {$user} -p{$pass} {$db} > {$tempPath}";
        system($comando, $resultado);
    
        if ($resultado === 0) {
            // 2. Força o navegador a baixar o arquivo (Aqui você escolhe a pasta)
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($tempPath) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($tempPath));
            
            readfile($tempPath);
    
            // 3. Deleta o temporário do servidor para não ocupar espaço
            unlink($tempPath);
            exit;
        } else {
            die("Erro ao gerar backup.");
        }
    }

    /**
     * Remove arquivos .sql com mais de 7 dias de vida
     */
    private function limparBackupsAntigos() {
        $arquivos = glob($this->backupDir . "*.sql");
        $agora = time();
        $seteDiasEmSegundos = 7 * 24 * 60 * 60;

        foreach ($arquivos as $arquivo) {
            if (is_file($arquivo)) {
                if ($agora - filemtime($arquivo) >= $seteDiasEmSegundos) {
                    unlink($arquivo);
                }
            }
        }
    }

    public function restaurar() {
        if (!isset($_FILES['backup_file'])) {
            header("Location: index.php?url=configuracao&status=error&msg=Arquivo+nao+enviado");
            exit;
        }

        $host = getenv('DB_HOST');
        $db   = getenv('DB_NAME');
        $user = getenv('DB_USER');
        $pass = getenv('DB_PASS');
        $tmp  = $_FILES['backup_file']['tmp_name'];

        $comando = "mysql -h {$host} -u {$user} -p{$pass} {$db} < {$tmp}";
        system($comando, $resultado);

        if ($resultado === 0) {
            header("Location: index.php?url=configuracao&status=success&msg=Base+restaurada+com+sucesso");
        } else {
            header("Location: index.php?url=configuracao&status=error&msg=Erro+na+restauracao");
        }
        exit;
    }
}