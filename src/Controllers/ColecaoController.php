<?php
namespace App\Controllers;

use App\Services\ColecaoService;

class ColecaoController {

    private $service;

    public function __construct() {
        $this->service = new ColecaoService();
    }

    public function index() {

        $pagina = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;

        $dados = $this->service->getGridColecao($pagina);

        extract($dados);

        include __DIR__ . '/../Views/colecao/grid.php';
    }

    public function listarFaixas() {

        header('Content-Type: application/json');

        $midiaId = filter_input(INPUT_GET, 'midia_id', FILTER_VALIDATE_INT);

        if (!$midiaId) {
            echo json_encode(['error' => 'ID inválido']);
            exit;
        }

        $faixas = $this->service->getFaixasPorMidia($midiaId);

        echo json_encode($faixas);
        exit;
    }

    public function descartarAlbum() {

        header('Content-Type: application/json');

        $midiaId = filter_input(INPUT_POST, 'midia_id', FILTER_VALIDATE_INT);

        if (!$midiaId) {
            echo json_encode([
                'success' => false,
                'error' => 'ID inválido'
            ]);
            exit;
        }

        $sucesso = $this->service->desativarMidia($midiaId);

        echo json_encode(['success' => $sucesso]);
        exit;
    }

    public function exibirFormularioEdicao($midia_id) {

        $midia_id = filter_var($midia_id, FILTER_VALIDATE_INT);

        if (!$midia_id) {
            header("Location: index.php?url=colecao");
            exit;
        }

        $album = $this->service->buscarDetalhesMidia($midia_id);
        $faixas = $this->service->getFaixasPorMidia($midia_id);
        $gravadoras = $this->service->buscarTodasGravadoras();

        if (!$album) {
            die("Álbum não encontrado.");
        }

        require_once __DIR__ . '/../Views/colecao/editar_album.php';
    }
}