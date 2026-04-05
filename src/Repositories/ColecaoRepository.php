<?php
namespace App\Repositories;

use App\Config\Database;
use PDO;

class ColecaoRepository {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function buscarParaGrid($limit = 25, $offset = 0, $filtros = []) {
        // 1. Iniciamos a query base
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
                WHERE tm.ativo = 1";

        // 2. Montamos o WHERE dinâmico
        $params = [];
        if (!empty($filtros['artista_id'])) {
            $sql .= " AND ta.artista_id = :artista_id";
            $params[':artista_id'] = (int)$filtros['artista_id'];
        }
        if (!empty($filtros['gravadora_id'])) {
            $sql .= " AND tm.gravadora_id = :gravadora_id";
            $params[':gravadora_id'] = (int)$filtros['gravadora_id'];
        }
        if (!empty($filtros['tipo_id'])) {
            $sql .= " AND ta.tipo_id = :tipo_id";
            $params[':tipo_id'] = (int)$filtros['tipo_id'];
        }
        if (!empty($filtros['situacao_id'])) {
            $sql .= " AND ta.situacao = :situacao_id";
            $params[':situacao_id'] = (int)$filtros['situacao_id'];
        }
        if (!empty($filtros['titulo'])) {
            $sql .= " AND ta.titulo LIKE :titulo";
            $params[':titulo'] = '%' . $filtros['titulo'] . '%';
        }

        if (!empty($filtros['produtor_id'])) {
            // Como é uma relação N:N, usamos um EXISTS para filtrar álbuns que tenham esse produtor
            $sql .= " AND EXISTS (
                SELECT 1 FROM tb_album_produtores tap 
                WHERE tap.album_id = ta.album_id 
                AND tap.produtor_id = :produtor_id
            )";
            $params[':produtor_id'] = (int)$filtros['produtor_id'];
        }

        $sql .= " ORDER BY tm.midia_id DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        
        // Fazemos o bind dos filtros dinâmicos
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }

        $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, \PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function contarTotal($filtros = []) {
        $sql = "SELECT COUNT(*) 
                FROM tb_midias tm
                INNER JOIN tb_albuns ta ON tm.album_id = ta.album_id
                WHERE tm.ativo = 1";

        $params = [];
        if (!empty($filtros['artista_id'])) {
            $sql .= " AND ta.artista_id = :artista_id";
            $params[':artista_id'] = (int)$filtros['artista_id'];
        }
        if (!empty($filtros['gravadora_id'])) {
            $sql .= " AND tm.gravadora_id = :gravadora_id";
            $params[':gravadora_id'] = (int)$filtros['gravadora_id'];
        }
        if (!empty($filtros['tipo_id'])) {
            $sql .= " AND ta.tipo_id = :tipo_id";
            $params[':tipo_id'] = (int)$filtros['tipo_id'];
        }
        if (!empty($filtros['situacao_id'])) {
            $sql .= " AND ta.situacao = :situacao_id";
            $params[':situacao_id'] = (int)$filtros['situacao_id'];
        }
        if (!empty($filtros['titulo'])) {
            $sql .= " AND ta.titulo LIKE :titulo";
            $params[':titulo'] = '%' . $filtros['titulo'] . '%';
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    public function buscarTodasGravadoras() {
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
        
        $stmt = $this->db->prepare($sql); 
        $stmt->bindValue(':midia_id', $midiaId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function marcarComoInativo($midiaId) {
        $sql = "UPDATE tb_midias SET ativo = 0 WHERE midia_id = :id";
        $stmt = $this->db->prepare($sql); 
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

    public function getAllFormatos() {
        $sql = "SELECT formato_id, descricao FROM tb_formatos ORDER BY descricao ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
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

    public function getAllSituacoes() {
        $sql = "SELECT situacao_id, descricao FROM tb_situacoes ORDER BY situacao_id ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
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

    public function salvarGeneros($albumId, array $generosNomes) {
        $sqlDelete = "DELETE FROM tb_album_generos WHERE album_id = :album_id";
        $stmtDelete = $this->db->prepare($sqlDelete);
        $stmtDelete->execute([':album_id' => $albumId]);
    
        foreach ($generosNomes as $nome) {
            $nome = trim($nome);
            if (empty($nome)) continue;
    
            $sqlCheck = "SELECT genero_id FROM tb_generos WHERE descricao = :nome";
            $stmtCheck = $this->db->prepare($sqlCheck);
            $stmtCheck->execute([':nome' => $nome]);
            $generoId = $stmtCheck->fetchColumn();
    
            if (!$generoId) {
                $sqlInsert = "INSERT INTO tb_generos (descricao) VALUES (:nome)";
                $this->db->prepare($sqlInsert)->execute([':nome' => $nome]);
                $generoId = $this->db->lastInsertId();
            }
    
            $sqlPivot = "INSERT INTO tb_album_generos (album_id, genero_id) VALUES (:album_id, :genero_id)";
            $this->db->prepare($sqlPivot)->execute([
                ':album_id' => $albumId,
                ':genero_id' => $generoId
            ]);
        }
    }

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
        $sqlDelete = "DELETE FROM tb_midia_faixas WHERE midia_id = ?";
        $this->db->prepare($sqlDelete)->execute([(int)$midiaId]);

        $sqlInsert = "INSERT INTO tb_midia_faixas (midia_id, numero_faixa, titulo, duracao) 
                      VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sqlInsert);

        foreach ($faixas as $faixa) {
            $titulo = trim($faixa['titulo'] ?? '');
            if (empty($titulo)) continue;

            $numero = $faixa['numero_faixa'] ?? ($faixa['posicao'] ?? 0);
            $duracaoRaw = $faixa['duracao'] ?? '';
            $duracao = !empty($duracaoRaw) ? $this->formatarDuracaoParaBanco($duracaoRaw) : '00:00:00';

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
        if (count($partes) == 2) return "00:{$partes[0]}:{$partes[1]}";
        if (count($partes) == 3) return $tempo;
        return null;
    }

    public function buscarDetalhesAlbum($album_id) {
        $sql = "SELECT 
                    ta.album_id,
                    ta.capa_url,
                    ta.titulo,
                    ta.artista_id,
                    art.nome AS artista_nome,
                    ta.gravadora_id,
                    tg.nome AS gravadora_nome,
                    ta.data_lancamento
                FROM
                    tb_albuns ta
                    INNER JOIN tb_artistas art ON ta.artista_id = art.artista_id
                    INNER JOIN tb_gravadoras tg ON ta.gravadora_id = tg.gravadora_id
                WHERE
                    ta.album_id = :album_id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':album_id', $album_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function inserirNovaMidia(array $dados) {
        $sql = "INSERT INTO tb_midias (album_id, formato_id, gravadora_id, data_aquisicao, preco, numero_catalogo, discogs_id, condicao, observacoes, ativo) 
                VALUES (:album_id, :formato_id, :gravadora_id, :data_aq, :preco, :cat, :d_id, :cond, :obs, 1)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':album_id'     => (int)$dados['album_id'],
            ':formato_id'   => (int)$dados['formato_id'],
            ':gravadora_id' => (int)$dados['gravadora_id'],
            ':data_aq'      => !empty($dados['data_aquisicao']) ? $dados['data_aquisicao'] : date('Y-m-d'),
            ':preco'        => $dados['preco'] ?? 0,
            ':cat'          => $dados['numero_catalogo'] ?? null,
            ':d_id'         => !empty($dados['discogs_id']) ? (int)$dados['discogs_id'] : null,
            ':cond'         => $dados['condicao'] ?? null,
            ':obs'          => $dados['observacoes'] ?? null
        ]);

        return $this->db->lastInsertId();
    }

    public function atualizarStatusAlbum($albumId, $statusId) {
        $sql = "UPDATE tb_albuns SET situacao = :status WHERE album_id = :id";
        return $this->db->prepare($sql)->execute([
            ':status' => (int)$statusId,
            ':id'     => (int)$albumId
        ]);
    }

    public function buscarOuCriarGravadora($nome) {
        $nome = trim($nome);
        $sql = "SELECT gravadora_id FROM tb_gravadoras WHERE nome = :nome LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':nome' => $nome]);
        $res = $stmt->fetch();
    
        if ($res) {
            return $res['gravadora_id'];
        }
    
        $sqlIns = "INSERT INTO tb_gravadoras (nome) VALUES (:nome)";
        $this->db->prepare($sqlIns)->execute([':nome' => $nome]);
    
        return $this->db->lastInsertId();
    }

    public function registrarExecucao($midiaId) {
        $sql = "UPDATE tb_midias 
                SET data_ultima_execucao = NOW() 
                WHERE midia_id = :id";
                
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => (int)$midiaId]);
    }
}