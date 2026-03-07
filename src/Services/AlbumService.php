<?php
namespace App\Services;

use App\Repositories\AlbumRepository;

class AlbumService {
    private $repository;

    public function __construct() {
        $this->repository = new AlbumRepository();
    }

    public function getListaPaginada($pagina, array $filters = []) {
        $limit = 25;
        $offset = (max(1, $pagina) - 1) * $limit;
        
        $albuns = $this->repository->findPaginated($limit, $offset, $filters);
        $total = $this->repository->getTotalCount($filters);
        
        return [
            'itens' => $albuns,
            'total' => $total,
            'paginas' => ceil($total / $limit)
        ];
    }

    public function atualizar($id, array $data) {
        // Regra: Se não tiver capa, define uma padrão
        if (empty($data['capa_url'])) {
            $data['capa_url'] = 'assets/images/placeholder.jpg';
        }
        return $this->repository->update($id, $data);
    }

    public function deletar($id) {
        return $this->repository->softDelete($id);
    }
}