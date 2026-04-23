<?php
namespace App\Controllers;

use App\Services\ArtistaService;

class ArtistaController {
    private $service;

    public function __construct() {
        $this->service = new ArtistaService();
    }

    public function index() {
        $pagina = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
        $dados = $this->service->getGridArtistas($pagina);
        
        extract($dados);
        include __DIR__ . '/../Views/artistas/grid.php';
    }

    public function salvarEdicaoArtista() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dados = [
                'artista_id'       => $_POST['artista_id'],
                'nome'             => $_POST['nome'],
                'imagem_url'       => $_POST['imagem_url'],
                'pais_origem'      => !empty($_POST['pais_origem']) ? (int)$_POST['pais_origem'] : null,
                'genero_principal' => !empty($_POST['genero_principal']) ? (int)$_POST['genero_principal'] : null,
                'ano_formacao'     => !empty($_POST['ano_formacao']) ? (int)$_POST['ano_formacao'] : null,
                'ano_encerramento' => !empty($_POST['ano_encerramento']) ? (int)$_POST['ano_encerramento'] : null,
                'biografia'        => $_POST['biografia'],
                'site_oficial'     => $_POST['site_oficial']
            ];

            $this->service->atualizarArtista($dados);
            header('Location: ?url=artistas');
            exit;
        }
    }}