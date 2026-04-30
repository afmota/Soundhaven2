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

    public function criarNovoAlbum(array $dados) {
        // Validação de segurança
        if (empty($dados['titulo']) || empty($dados['artista_id'])) {
            throw new \Exception("Dados obrigatórios faltando.");
        }
        return $this->repository->create($dados);
    }

    public function importarCsv($caminhoArquivo) {
        if (!file_exists($caminhoArquivo) || !is_readable($caminhoArquivo)) {
            return false;
        }

        $handle = fopen($caminhoArquivo, 'r');
        if ($handle === false) return false;

        // Pula o cabeçalho
        fgetcsv($handle, 1000, ",");

        // O ERRO ESTAVA AQUI: Usamos a conexão que está dentro do repository
        $db = \App\Config\Database::getConnection(); 
        $db->beginTransaction();

        try {
            while (($linha = fgetcsv($handle, 1000, ",")) !== false) {
                $dados = [
                    'titulo'          => $linha[0],
                    'capa_url'        => $linha[1] ?: null,
                    'artista_id'      => (int)$linha[2],
                    'gravadora_id'    => $linha[3] ? (int)$linha[3] : null,
                    'data_lancamento' => $linha[4] ?: null,
                    'tipo_id'         => (int)$linha[5],
                    'situacao'        => (int)$linha[6]
                ];

                $this->repository->create($dados);
            }
            
            $db->commit();
            fclose($handle);
            return true;
        } catch (\Exception $e) {
            $db->rollBack();
            fclose($handle);
            // Opcional: logar o erro $e->getMessage() para saber o que falhou
            return false;
        }
    }
}