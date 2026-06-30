<?php
namespace App\Controllers;

class ConfiguracaoController {
    public function index() {
        header('Location: index.php?url=dashboard');
        exit;
    }
}
