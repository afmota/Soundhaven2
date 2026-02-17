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
}