<?php

namespace App\Core\Services;

use App\Core\Entities\Album;
use App\Infrastructure\Repositories\MySqlAlbumRepository;
use Exception;

class AlbumService {
    public function __construct(private MySqlAlbumRepository $repository) {}

    public function contarComFiltros(array $filtros, int $userId): int {
        return $this->repository->countWithFilters($filtros, $userId);
    }

    public function listarParaVitrine(array $filtros, int $userId, int $limit, int $offset): array {
        return $this->repository->findWithFilters($filtros, $userId, $limit, $offset);
    }

    public function listarArtistasDoUsuario(int $userId): array {
        return $this->repository->buscarArtistasPorUsuario($userId);
    }

    public function atualizarAlbum(array $dados, int $userId): bool {
        if (empty($dados['id']) || empty($dados['titulo'])) {
            throw new Exception("Dados obrigatórios para atualização estão ausentes.");
        }

        $album = new Album(
            (int)$dados['id'],
            $dados['titulo'],
            $dados['capa_url'] ?? null,
            (int)$dados['artista_id'],
            $dados['data_lancamento'],
            (int)$dados['tipo_id'],
            (int)$dados['situacao'],
            '', 
            ''  
        );

        return $this->repository->update($album, $userId);
    }

    /**
     * Lógica de serviço para exclusão lógica
     */
    public function excluirAlbum(int $id, int $userId): bool {
        if ($id <= 0) {
            throw new Exception("Identificador de álbum inválido.");
        }
        return $this->repository->softDelete($id, $userId);
    }

    /**
     * NOVO MÉTODO: Lógica de serviço para salvar novo álbum
     * Mantém o padrão de criação de entidade Album antes de enviar ao repository
     */
    public function salvarNovoAlbum(array $dados, int $userId): int {
        if (empty($dados['titulo']) || empty($dados['artista_id'])) {
            throw new Exception("Título e Artista são obrigatórios.");
        }

        $album = new Album(
            0, // ID 0 pois será gerado pelo banco
            $dados['titulo'],
            $dados['capa_url'] ?? 'https://placehold.co/300x300?text=Sem+Capa',
            (int)$dados['artista_id'],
            $dados['data_lancamento'],
            (int)$dados['tipo_id'],
            (int)$dados['situacao'],
            '', 
            ''  
        );

        return $this->repository->create($album, $userId);
    }
}