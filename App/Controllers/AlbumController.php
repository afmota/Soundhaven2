<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Album;
use App\Models\Tipo;
use App\Models\Situacao;

class AlbumController extends Controller
{
    protected Album $albumModel;
    protected Tipo $tipoModel;
    protected Situacao $situacaoModel;

    public function __construct()
    {
        parent::__construct();

        $this->albumModel    = new Album();
        $this->tipoModel     = new Tipo();
        $this->situacaoModel = new Situacao();
    }

    /**
     * GET /albuns
     */
    public function index(): void
    {
        $albuns = $this->albumModel->all(50, 0);

        $this->render('album/index', [
            'tituloPagina' => 'Álbuns',
            'albuns'       => $albuns
        ]);
    }

    /**
     * GET /albuns/novo
     */
    public function novo(): void
    {
        $tipos     = $this->tipoModel->all();
        $situacoes = $this->situacaoModel->all();

        $this->render('album/form', [
            'tituloPagina' => 'Novo Álbum',
            'tipos'        => $tipos,
            'situacoes'    => $situacoes,
            'album'        => null
        ]);
    }

    /**
     * GET /albuns/editar/{id}
     */
    public function editar(int $id): void
    {
        $album = $this->albumModel->findById($id);

        if (!$album) {
            $this->redirect('/albuns');
            return;
        }

        $tipos     = $this->tipoModel->all();
        $situacoes = $this->situacaoModel->all();

        $this->render('album/form', [
            'tituloPagina' => 'Editar Álbum',
            'album'        => $album,
            'tipos'        => $tipos,
            'situacoes'    => $situacoes
        ]);
    }

    /**
 * POST /albuns/salvar
 */
public function store(): void
{
    $data = $this->sanitize($_POST);

    $errors = $this->validate($data);

    if (!empty($errors)) {
        $this->render('album/form', [
            'tituloPagina' => 'Novo Álbum',
            'album'        => $data,
            'tipos'        => $this->tipoModel->all(),
            'situacoes'    => $this->situacaoModel->all(),
            'errors'       => $errors
        ]);
        return;
    }

    $this->albumModel->create($data);

    $this->redirect('/albuns');
}

/**
 * POST /albuns/atualizar/{id}
 */
public function update(int $id): void
{
    $data = $this->sanitize($_POST);

    $errors = $this->validate($data);

    if (!empty($errors)) {
        $data['id'] = $id;

        $this->render('album/form', [
            'tituloPagina' => 'Editar Álbum',
            'album'        => $data,
            'tipos'        => $this->tipoModel->all(),
            'situacoes'    => $this->situacaoModel->all(),
            'errors'       => $errors
        ]);
        return;
    }

    $this->albumModel->update($id, $data);

    $this->redirect('/albuns');
}

protected function sanitize(array $data): array
{
    return [
        'titulo'          => trim($data['titulo'] ?? ''),
        'capa_url'        => $data['capa_url'] ?: null,
        'data_lancamento' => $data['data_lancamento'] ?: null,
        'tipo_id'         => $data['tipo_id'] ?: null,
        'situacao'        => $data['situacao'] ?: null,
        'preco_sugerido'  => $data['preco_sugerido'] ?: null,
        'artista_id'      => null, // ainda não tratamos artista
    ];
}

protected function validate(array $data): array
{
    $errors = [];

    if ($data['titulo'] === '') {
        $errors[] = 'O título é obrigatório.';
    }

    if ($data['preco_sugerido'] !== null && !is_numeric($data['preco_sugerido'])) {
        $errors[] = 'O preço precisa ser numérico.';
    }

    return $errors;
}

}
