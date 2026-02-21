<?php

namespace App\Core\Services;

use App\Infrastructure\Repositories\MySqlColecaoRepository;

class ColecaoService {
    public function __construct(private MySqlColecaoRepository $repository) {}

    public function listarColecao(int $limit, int $offset, array $filtros = []): array {
        return $this->repository->listarItensColecao($limit, $offset, $filtros);
    }

    public function getTotalItens(array $filtros = []): int {
        return $this->repository->contarTotalColecao($filtros);
    }

    public function getTiposAlbum(): array {
        return $this->repository->listarTodosTipos();
    }

    public function getGravadoras(): array {
        return $this->repository->listarTodasGravadoras();
    }

    public function getFormatos(): array {
        return $this->repository->listarTodosFormatos();
    }

    public function getProdutores(): array {
        return $this->repository->listarTodosProdutores();
    }

    public function getGeneros(): array {
        return $this->repository->listarTodosGeneros();
    }

    public function getEstilos(): array {
        return $this->repository->listarTodosEstilos();
    }
}