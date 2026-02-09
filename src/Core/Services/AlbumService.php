<?php

namespace App\Core\Services;

use App\Infrastructure\Repositories\MySqlAlbumRepository;

class AlbumService
{
    public function __construct(private MySqlAlbumRepository $repository) {}

    /**
     * Repassa a busca da lista de artistas para o Repositório
     */
    public function listarArtistasDoUsuario(int $userId): array
    {
        return $this->repository->buscarArtistasPorUsuario($userId);
    }

    /**
     * Repassa a contagem total com filtros para o Repositório
     */
    public function contarComFiltros(array $filters, int $userId): int
    {
        return $this->repository->countWithFilters($filters, $userId);
    }

    /**
     * Repassa a listagem da vitrine para o Repositório
     */
    public function listarParaVitrine(array $filters, int $userId, int $limit, int $offset): array
    {
        return $this->repository->findWithFilters($filters, $userId, $limit, $offset);
    }
}