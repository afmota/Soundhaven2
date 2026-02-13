<?php

namespace App\Core\Services;

use App\Core\Entities\Album;
use App\Infrastructure\Repositories\MySqlAlbumRepository;
use Exception;

class AlbumService {
    public function __construct(private MySqlAlbumRepository $repository) {}

    /**
     * Retorna a contagem total de álbuns baseada nos filtros.
     */
    public function contarComFiltros(array $filtros, int $userId): int {
        return $this->repository->countWithFilters($filtros, $userId);
    }

    /**
     * Retorna a lista de álbuns para a vitrine.
     */
    public function listarParaVitrine(array $filtros, int $userId, int $limit, int $offset): array {
        return $this->repository->findWithFilters($filtros, $userId, $limit, $offset);
    }

    /**
     * Retorna a lista de artistas para popular dropdowns.
     */
    public function listarArtistasDoUsuario(int $userId): array {
        return $this->repository->buscarArtistasPorUsuario($userId);
    }

    /**
     * Executa a atualização de um álbum.
     * Centraliza a lógica de transformação de dados para a Entidade.
     */
    public function atualizarAlbum(array $dados, int $userId): bool {
        if (empty($dados['id']) || empty($dados['titulo'])) {
            throw new Exception("Dados obrigatórios para atualização estão ausentes.");
        }

        // Criamos a entidade para garantir a integridade dos dados antes de enviar ao repositório
        $album = new Album(
            (int)$dados['id'],
            $dados['titulo'],
            $dados['capa_url'] ?? null,
            (int)$dados['artista_id'],
            $dados['data_lancamento'],
            (int)$dados['tipo_id'],
            (int)$dados['situacao'],
            '', // artistaNome (não necessário para persistência)
            ''  // data_criacao (não necessário para persistência)
        );

        return $this->repository->update($album, $userId);
    }
}