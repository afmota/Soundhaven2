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
        $totalPaginas = ceil($total / $limit);
    
        // --- MÁGICA DA PAGINAÇÃO (A JANELA) ---
        $maxBotoes = 5; // Quantos números você quer que apareçam?
        $inicioPagina = max(1, $pagina - 2);
        $fimPagina = min($totalPaginas, $inicioPagina + $maxBotoes - 1);
    
        // Ajuste caso estejamos nas últimas páginas
        if ($fimPagina - $inicioPagina < $maxBotoes - 1) {
            $inicioPagina = max(1, $fimPagina - $maxBotoes + 1);
        }
    
        return [
            'itens'         => $albuns,
            'totalPaginas'  => $totalPaginas,
            'paginaAtual'   => $pagina,
            'inicioPagina'  => $inicioPagina,
            'fimPagina'     => $fimPagina
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
    public function salvarEdicao($id, array $postData) {
            // Validação simples
            if (empty($postData['titulo'])) {
                return false;
            }
    
            // Aqui você poderia redimensionar imagem, logar quem editou, etc.
            return $this->repository->update($id, $postData);
        }
}