<?php
namespace App\Repositories;

use App\Config\Database;
use PDO;

class ColecaoRepository {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function buscarParaGrid($limit = 25, $offset = 0) {
        $sql = "SELECT 
                    tm.*, ta.titulo, ta.capa_url, ta.data_lancamento,
                    art.nome AS artista_nome, tg.nome AS gravadora_nome,
                    tf.descricao AS formato_nome, tf.cor_hex AS formato_cor,
                    (SELECT GROUP_CONCAT(p.nome SEPARATOR '|') 
                     FROM tb_album_produtores ap 
                     JOIN tb_produtores p ON ap.produtor_id = p.produtor_id 
                     WHERE ap.album_id = ta.album_id) as produtores,
                    (SELECT GROUP_CONCAT(g.descricao SEPARATOR '|') 
                     FROM tb_album_generos ag 
                     JOIN tb_generos g ON ag.genero_id = g.genero_id 
                     WHERE ag.album_id = ta.album_id) as generos,
                    (SELECT GROUP_CONCAT(e.descricao SEPARATOR '|') 
                     FROM tb_album_estilos ae 
                     JOIN tb_estilos e ON ae.estilo_id = e.estilo_id 
                     WHERE ae.album_id = ta.album_id) as estilos
                FROM tb_midias tm
                INNER JOIN tb_albuns ta ON tm.album_id = ta.album_id
                INNER JOIN tb_artistas art ON ta.artista_id = art.artista_id
                INNER JOIN tb_gravadoras tg ON tm.gravadora_id = tg.gravadora_id
                INNER JOIN tb_formatos tf ON tm.formato_id = tf.formato_id
                WHERE tm.ativo = 1
                ORDER BY tm.data_aquisicao DESC
                LIMIT :limit OFFSET :offset";    
                $stmt = $this->db->prepare($sql);
        
        // O segredo aqui é garantir o (int) e o \PDO::PARAM_INT
        $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, \PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

public function contarTotal() {

    $sql = "SELECT COUNT(*) 
            FROM tb_midias
            WHERE ativo = 1";

    return $this->db->query($sql)->fetchColumn();
}

public function buscarTodasGravadoras()
{
    $sql = "SELECT gravadora_id, nome
            FROM tb_gravadoras
            ORDER BY nome";

    return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

public function buscarDetalhesMidia($midiaId) {
    $sql = "SELECT 
                tm.midia_id, tm.album_id, ta.titulo, ta.capa_url,
                art.artista_id, art.nome AS artista_nome,
                tg.gravadora_id, tg.nome AS gravadora_nome,
                tf.formato_id, tf.descricao AS formato_nome,
                tt.tipo_id, tt.descricao AS tipo_nome,
                tm.numero_catalogo, ta.data_lancamento, tm.data_aquisicao,
                tm.preco, tm.condicao, tm.observacoes,
                
                -- AS SUB-QUERIES QUE ESTAVAM FALTANDO AQUI:
                (SELECT GROUP_CONCAT(p.nome SEPARATOR '|') 
                 FROM tb_album_produtores ap 
                 JOIN tb_produtores p ON ap.produtor_id = p.produtor_id 
                 WHERE ap.album_id = ta.album_id) as produtores,
                (SELECT GROUP_CONCAT(g.descricao SEPARATOR '|') 
                 FROM tb_album_generos ag 
                 JOIN tb_generos g ON ag.genero_id = g.genero_id 
                 WHERE ag.album_id = ta.album_id) as generos,
                (SELECT GROUP_CONCAT(e.descricao SEPARATOR '|') 
                 FROM tb_album_estilos ae 
                 JOIN tb_estilos e ON ae.estilo_id = e.estilo_id 
                 WHERE ae.album_id = ta.album_id) as estilos

            FROM tb_midias tm
            INNER JOIN tb_albuns ta ON tm.album_id = ta.album_id
            INNER JOIN tb_gravadoras tg ON tm.gravadora_id = tg.gravadora_id
            INNER JOIN tb_formatos tf ON tm.formato_id = tf.formato_id
            INNER JOIN tb_artistas art ON ta.artista_id = art.artista_id
            INNER JOIN tb_tipos tt ON ta.tipo_id = tt.tipo_id
            WHERE tm.midia_id = :id";

    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':id', $midiaId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

    public function buscarFaixasPorMidia($midiaId) {
        $sql = "SELECT numero_faixa, titulo, duracao 
                FROM tb_midia_faixas 
                WHERE midia_id = :midia_id 
                ORDER BY numero_faixa ASC";
        
        $stmt = $this->db->prepare($sql); // Assumindo que seu repository usa $this->db para a conexão
        $stmt->bindValue(':midia_id', $midiaId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function marcarComoInativo($midiaId) {
        $sql = "UPDATE tb_midias SET ativo = 0 WHERE midia_id = :id";
        $stmt = $this->db->prepare($sql); // ou como você chama a conexão no Repository
        return $stmt->execute([':id' => $midiaId]);
    }

    public function getAllArtistas() {
        $sql = "SELECT artista_id, nome FROM tb_artistas ORDER BY nome ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllGravadoras() {
        $sql = "SELECT gravadora_id, nome FROM tb_gravadoras ORDER BY nome ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllTipos() {
        $sql = "SELECT tipo_id, descricao FROM tb_tipos ORDER BY descricao ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllGeneros() {
        return $this->db->query("SELECT descricao FROM tb_generos ORDER BY descricao ASC")->fetchAll(\PDO::FETCH_COLUMN);
    }
    
    public function getAllEstilos() {
        return $this->db->query("SELECT descricao FROM tb_estilos ORDER BY descricao ASC")->fetchAll(\PDO::FETCH_COLUMN);
    }
    
    public function getAllProdutores() {
        return $this->db->query("SELECT nome FROM tb_produtores ORDER BY nome ASC")->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function iniciarTransacao() {
        return $this->db->beginTransaction();
    }

    public function confirmarTransacao() {
        return $this->db->commit();
    }

    public function cancelarTransacao() {
        return $this->db->rollBack();
    }

// No ColecaoRepository.php
    public function salvarGeneros($albumId, array $generosNomes) {
        // 1. Remove as associações antigas
        $sqlDelete = "DELETE FROM tb_album_generos WHERE album_id = :album_id";
        $stmtDelete = $this->db->prepare($sqlDelete);
        $stmtDelete->execute([':album_id' => $albumId]);
    
        foreach ($generosNomes as $nome) {
            $nome = trim($nome);
            if (empty($nome)) continue;
    
            // 2. Busca ou Cria o gênero na tb_generos
            $sqlCheck = "SELECT genero_id FROM tb_generos WHERE descricao = :nome";
            $stmtCheck = $this->db->prepare($sqlCheck);
            $stmtCheck->execute([':nome' => $nome]);
            $generoId = $stmtCheck->fetchColumn();
    
            if (!$generoId) {
                $sqlInsert = "INSERT INTO tb_generos (descricao) VALUES (:nome)";
                $this->db->prepare($sqlInsert)->execute([':nome' => $nome]);
                $generoId = $this->db->lastInsertId();
            }
    
            // 3. Associa na tabela pivô
            $sqlPivot = "INSERT INTO tb_album_generos (album_id, genero_id) VALUES (:album_id, :genero_id)";
            $this->db->prepare($sqlPivot)->execute([
                ':album_id' => $albumId,
                ':genero_id' => $generoId
            ]);
        }
    }

    // Sincronizar Estilos
    public function salvarEstilos($albumId, array $estilosNomes) {
        $this->db->prepare("DELETE FROM tb_album_estilos WHERE album_id = :id")
                 ->execute([':id' => $albumId]);

        foreach ($estilosNomes as $nome) {
            $nome = trim($nome);
            if (empty($nome)) continue;

            $stmt = $this->db->prepare("SELECT estilo_id FROM tb_estilos WHERE descricao = :nome");
            $stmt->execute([':nome' => $nome]);
            $id = $stmt->fetchColumn();

            if (!$id) {
                $this->db->prepare("INSERT INTO tb_estilos (descricao) VALUES (:nome)")
                         ->execute([':nome' => $nome]);
                $id = $this->db->lastInsertId();
            }

            $this->db->prepare("INSERT INTO tb_album_estilos (album_id, estilo_id) VALUES (?, ?)")
                     ->execute([$albumId, $id]);
        }
    }

    // Sincronizar Produtores
    public function salvarProdutores($albumId, array $produtoresNomes) {
        $this->db->prepare("DELETE FROM tb_album_produtores WHERE album_id = :id")
                 ->execute([':id' => $albumId]);

        foreach ($produtoresNomes as $nome) {
            $nome = trim($nome);
            if (empty($nome)) continue;

            $stmt = $this->db->prepare("SELECT produtor_id FROM tb_produtores WHERE nome = :nome");
            $stmt->execute([':nome' => $nome]);
            $id = $stmt->fetchColumn();

            if (!$id) {
                $this->db->prepare("INSERT INTO tb_produtores (nome) VALUES (:nome)")
                         ->execute([':nome' => $nome]);
                $id = $this->db->lastInsertId();
            }

            $this->db->prepare("INSERT INTO tb_album_produtores (album_id, produtor_id) VALUES (?, ?)")
                     ->execute([$albumId, $id]);
        }
    }

    public function updateDadosBasicos($midiaId, $albumId, $dados) {
        // 1. Atualiza a Obra (Álbum) - Esta parte parece ok
        $sqlA = "UPDATE tb_albuns SET 
                    titulo = :titulo, 
                    artista_id = :artista_id, 
                    capa_url = :capa_url,
                    data_lancamento = :data_lancto,
                    tipo_id = :tipo_id
                 WHERE album_id = :album_id";

        $this->db->prepare($sqlA)->execute([
            ':titulo'      => $dados['titulo'],
            ':artista_id'  => $dados['artista_id'],
            ':capa_url'    => $dados['capa_url'],
            ':data_lancto' => !empty($dados['data_lancamento']) ? $dados['data_lancamento'] : null,
            ':tipo_id'     => $dados['tipo_id'],
            ':album_id'    => $albumId
        ]);

        // 2. Update na tb_midias - CORRIGIDO
        $sqlM = "UPDATE tb_midias SET 
                    gravadora_id = :gravadora_id,
                    data_aquisicao = :data_aq,
                    preco = :preco,
                    numero_catalogo = :cat,
                    discogs_id = :d_id,
                    condicao = :cond,
                    observacoes = :obs
                 WHERE midia_id = :midia_id";

        $this->db->prepare($sqlM)->execute([
            ':gravadora_id'    => $dados['gravadora_id'],
            ':data_aq'         => !empty($dados['data_aquisicao']) ? $dados['data_aquisicao'] : null,
            ':preco'           => $dados['preco'],
            ':cat'             => $dados['numero_catalogo'] ?? null,
            ':d_id'            => (!empty($dados['discogs_id'])) ? (int)$dados['discogs_id'] : null,
            ':cond'            => $dados['condicao'] ?? null,
            ':obs'             => $dados['observacoes'] ?? null,
            ':midia_id'        => (int)$midiaId
        ]);
    }

    public function salvarFaixas($midiaId, array $faixas) {
        // 1. O EXTERMÍNIO: Remove as faixas antigas para reinserir as novas (ou importadas)
        $sqlDelete = "DELETE FROM tb_midia_faixas WHERE midia_id = ?";
        $this->db->prepare($sqlDelete)->execute([(int)$midiaId]);

        // 2. A PREPARAÇÃO: Usamos '?' para evitar o erro 'Invalid parameter number'
        $sqlInsert = "INSERT INTO tb_midia_faixas (midia_id, numero_faixa, titulo, duracao) 
                      VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sqlInsert);

        foreach ($faixas as $faixa) {
            $titulo = trim($faixa['titulo'] ?? '');

            // Se a faixa não tiver título (vazia), ignoramos e pulamos para a próxima
            if (empty($titulo)) continue;

            // O Discogs manda 'posicao', o seu form manda 'numero_faixa'. Pegamos o que existir.
            $numero = $faixa['numero_faixa'] ?? ($faixa['posicao'] ?? 0);

            // Tratamento da Duração
            $duracaoRaw = $faixa['duracao'] ?? '';
            $duracao = !empty($duracaoRaw) ? $this->formatarDuracaoParaBanco($duracaoRaw) : '00:00:00';

            // Execução direta e segura
            $stmt->execute([
                (int)$midiaId,
                (int)$numero,
                (string)$titulo,
                (string)$duracao
            ]);
        }
    }

    private function formatarDuracaoParaBanco($tempo) {
        if (empty($tempo)) return null;
        $partes = explode(':', $tempo);

        // Se mandou MM:SS, vira 00:MM:SS
        if (count($partes) == 2) return "00:{$partes[0]}:{$partes[1]}";
        // Se mandou HH:MM:SS, mantém
        if (count($partes) == 3) return $tempo;

        return null;
    }
}