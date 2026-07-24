<?php
namespace App\Controllers;

use App\Config\Database;
use App\Repositories\ColecaoRepository;
use Mpdf\Mpdf;

class RelatorioController {
    
    public function index() {
        $repo = new ColecaoRepository();
        
        $artistas = $repo->buscarArtistasComAlbunsNaColecao();
        $formatos = $repo->getAllFormatos();
        $gravadoras = $repo->buscarTodasGravadoras();
        $generos = $repo->getAllGeneros();
        $estilos = $repo->getAllEstilos();
        
        // Combina e ordena os gêneros/estilos alfabeticamente
        $generosEstilos = array_unique(array_merge($generos, $estilos));
        sort($generosEstilos);

        require_once __DIR__ . '/../Views/relatorios/index.php';
    }

    public function gerar() {
        $db = Database::getConnection();
        
        $sql = "SELECT 
                    tm.midia_id,
                    ta.album_id,
                    ta.titulo AS album_titulo,
                    ta.capa_url AS album_capa_url,
                    ta.data_lancamento AS album_data_lancamento,
                    art.nome AS artista_nome,
                    tg.nome AS gravadora_nome,
                    tf.descricao AS formato_nome,
                    tm.data_aquisicao AS midia_data_aquisicao,
                    (SELECT GROUP_CONCAT(g.descricao SEPARATOR ', ') 
                     FROM tb_album_generos ag 
                     JOIN tb_generos g ON ag.genero_id = g.genero_id 
                     WHERE ag.album_id = ta.album_id) as generos,
                    (SELECT GROUP_CONCAT(e.descricao SEPARATOR ', ') 
                     FROM tb_album_estilos ae 
                     JOIN tb_estilos e ON ae.estilo_id = e.estilo_id 
                     WHERE ae.album_id = ta.album_id) as estilos
                FROM tb_midias tm
                INNER JOIN tb_albuns ta ON tm.album_id = ta.album_id
                INNER JOIN tb_artistas art ON ta.artista_id = art.artista_id
                INNER JOIN tb_gravadoras tg ON tm.gravadora_id = tg.gravadora_id
                INNER JOIN tb_formatos tf ON tm.formato_id = tf.formato_id
                WHERE tm.ativo = 1";

        $params = [];
        
        // 1. Filtro de Artistas
        $artistas_tipo = $_POST['artistas_tipo'] ?? $_GET['artistas_tipo'] ?? 'todos';
        if ($artistas_tipo === 'especifico' && !empty($_POST['artista_id'])) {
            $sql .= " AND ta.artista_id = :artista_id";
            $params[':artista_id'] = (int)$_POST['artista_id'];
        } elseif ($artistas_tipo === 'multiplos' && !empty($_POST['artista_ids'])) {
            $ids = array_map('intval', $_POST['artista_ids']);
            if (!empty($ids)) {
                $inClause = [];
                foreach ($ids as $idx => $id) {
                    $paramName = ":art_id_" . $idx;
                    $inClause[] = $paramName;
                    $params[$paramName] = $id;
                }
                $sql .= " AND ta.artista_id IN (" . implode(', ', $inClause) . ")";
            }
        }

        // 2. Intervalo Temporal
        $tipo_data = $_POST['tipo_data'] ?? $_GET['tipo_data'] ?? 'lancamento';
        $data_inicio = $_POST['data_inicio'] ?? $_GET['data_inicio'] ?? '';
        $data_fim = $_POST['data_fim'] ?? $_GET['data_fim'] ?? '';
        $colData = ($tipo_data === 'aquisicao') ? 'tm.data_aquisicao' : 'ta.data_lancamento';

        if (!empty($data_inicio)) {
            $sql .= " AND {$colData} >= :data_inicio";
            $params[':data_inicio'] = $data_inicio;
        }
        if (!empty($data_fim)) {
            $sql .= " AND {$colData} <= :data_fim";
            $params[':data_fim'] = $data_fim;
        }

        // 3. Metadados - Gênero/Estilo
        if (!empty($_POST['generos']) && is_array($_POST['generos'])) {
            $genFilters = [];
            foreach ($_POST['generos'] as $idx => $gen) {
                $paramGen = ":gen_" . $idx;
                $genFilters[] = $paramGen;
                $params[$paramGen] = $gen;
            }
            $genPlaceholders = implode(', ', $genFilters);
            $sql .= " AND (
                EXISTS (
                    SELECT 1 FROM tb_album_generos tag
                    JOIN tb_generos g ON tag.genero_id = g.genero_id
                    WHERE tag.album_id = ta.album_id AND g.descricao IN ({$genPlaceholders})
                ) OR EXISTS (
                    SELECT 1 FROM tb_album_estilos tae
                    JOIN tb_estilos e ON tae.estilo_id = e.estilo_id
                    WHERE tae.album_id = ta.album_id AND e.descricao IN ({$genPlaceholders})
                )
            )";
        }

        // 4. Metadados - Formatos
        if (!empty($_POST['formatos']) && is_array($_POST['formatos'])) {
            $formIds = array_map('intval', $_POST['formatos']);
            if (!empty($formIds)) {
                $inForm = [];
                foreach ($formIds as $idx => $fid) {
                    $paramName = ":form_id_" . $idx;
                    $inForm[] = $paramName;
                    $params[$paramName] = $fid;
                }
                $sql .= " AND tm.formato_id IN (" . implode(', ', $inForm) . ")";
            }
        }

        // 5. Metadados - Gravadoras
        if (!empty($_POST['gravadoras']) && is_array($_POST['gravadoras'])) {
            $gravIds = array_map('intval', $_POST['gravadoras']);
            if (!empty($gravIds)) {
                $inGrav = [];
                foreach ($gravIds as $idx => $gid) {
                    $paramName = ":grav_id_" . $idx;
                    $inGrav[] = $paramName;
                    $params[$paramName] = $gid;
                }
                $sql .= " AND tm.gravadora_id IN (" . implode(', ', $inGrav) . ")";
            }
        }

        // 6. Ordenação
        $ordem = $_POST['ordem'] ?? $_GET['ordem'] ?? 'artista';
        $direcao = $_POST['direcao'] ?? $_GET['direcao'] ?? 'ASC';
        if (!in_array($direcao, ['ASC', 'DESC'])) {
            $direcao = 'ASC';
        }

        $ordemCampos = [
            'artista'    => 'art.nome',
            'album'      => 'ta.titulo',
            'lancamento' => 'ta.data_lancamento',
            'compra'     => 'tm.data_aquisicao',
            'gravadora'  => 'tg.nome'
        ];
        $campoOrdenacao = $ordemCampos[$ordem] ?? 'art.nome';
        
        if ($ordem === 'artista') {
            $sql .= " ORDER BY {$campoOrdenacao} {$direcao}, ta.data_lancamento ASC";
        } else {
            $sql .= " ORDER BY {$campoOrdenacao} {$direcao}";
        }

        // Executar query preparada
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $itens = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // 7. Consolidação de Filtros Aplicados para o PDF
        $filtrosAplicados = [];
        
        if ($artistas_tipo === 'todos') {
            $filtrosAplicados[] = "Artistas: Todos";
        } elseif ($artistas_tipo === 'especifico' && !empty($_POST['artista_id'])) {
            $st = $db->prepare("SELECT nome FROM tb_artistas WHERE artista_id = ?");
            $st->execute([$_POST['artista_id']]);
            $artNome = $st->fetchColumn();
            $filtrosAplicados[] = "Artista: " . ($artNome ?: "ID " . $_POST['artista_id']);
        } elseif ($artistas_tipo === 'multiplos' && !empty($_POST['artista_ids'])) {
            $filtrosAplicados[] = "Artistas: Seleção Personalizada (" . count($_POST['artista_ids']) . ")";
        }

        if (!empty($data_inicio) || !empty($data_fim)) {
            $labelData = ($tipo_data === 'aquisicao') ? "Aquisição" : "Lançamento";
            $ini = !empty($data_inicio) ? date('d/m/Y', strtotime($data_inicio)) : 'Início';
            $fim = !empty($data_fim) ? date('d/m/Y', strtotime($data_fim)) : 'Fim';
            $filtrosAplicados[] = "Período ({$labelData}): {$ini} a {$fim}";
        }

        if (!empty($_POST['generos']) && is_array($_POST['generos'])) {
            $filtrosAplicados[] = "Gêneros: " . implode(', ', $_POST['generos']);
        }

        if (!empty($_POST['formatos']) && is_array($_POST['formatos'])) {
            $placeholders = implode(',', array_fill(0, count($_POST['formatos']), '?'));
            $st = $db->prepare("SELECT descricao FROM tb_formatos WHERE formato_id IN ($placeholders)");
            $st->execute(array_map('intval', $_POST['formatos']));
            $fNomes = $st->fetchAll(\PDO::FETCH_COLUMN);
            $filtrosAplicados[] = "Formatos: " . implode(', ', $fNomes);
        }

        if (!empty($_POST['gravadoras']) && is_array($_POST['gravadoras'])) {
            $placeholders = implode(',', array_fill(0, count($_POST['gravadoras']), '?'));
            $st = $db->prepare("SELECT nome FROM tb_gravadoras WHERE gravadora_id IN ($placeholders)");
            $st->execute(array_map('intval', $_POST['gravadoras']));
            $gNomes = $st->fetchAll(\PDO::FETCH_COLUMN);
            $filtrosAplicados[] = "Gravadoras: " . implode(', ', $gNomes);
        }

        $resumoFiltros = implode(' | ', $filtrosAplicados);
        $incluir_capas_albuns = isset($_POST['incluir_capa']) && $_POST['incluir_capa'] == '1';

        // 8. Construção do HTML do PDF
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
        <meta charset="utf-8">
        <style>
            @page {
                footer: html_myFooter;
            }
            @page cover {
                footer: none;
            }
            body {
                font-family: Helvetica, Arial, sans-serif;
                color: #1e293b;
                font-size: 11px;
                line-height: 1.5;
            }
            .cover-container {
                text-align: center;
                padding-top: 100px;
                height: 100%;
            }
            .cover-title {
                font-size: 28px;
                font-weight: bold;
                color: #0f172a;
                margin-top: 20px;
                margin-bottom: 10px;
            }
            .cover-subtitle {
                font-size: 16px;
                color: #475569;
                margin-bottom: 80px;
            }
            .cover-box {
                background-color: #f8fafc;
                border: 1px solid #e2e8f0;
                border-radius: 8px;
                padding: 30px;
                margin: 0 auto;
                width: 80%;
                text-align: left;
            }
            .cover-box h3 {
                color: #0f172a;
                margin-top: 0;
                border-bottom: 2px solid #cbd5e1;
                padding-bottom: 10px;
                font-size: 16px;
            }
            .cover-meta-item {
                margin-bottom: 12px;
                font-size: 12px;
            }
            .cover-meta-label {
                font-weight: bold;
                color: #475569;
            }
            
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 10px;
            }
            th {
                background-color: #0f172a;
                color: #ffffff;
                font-weight: bold;
                text-align: left;
                padding: 8px;
                font-size: 9px;
                border: 1px solid #0f172a;
            }
            td {
                padding: 8px;
                border-bottom: 1px solid #e2e8f0;
                border-left: 1px solid #e2e8f0;
                border-right: 1px solid #e2e8f0;
                font-size: 9px;
            }
            tr:nth-child(even) td {
                background-color: #f8fafc;
            }
        </style>
        </head>
        <body>
        
        <htmlpagefooter name="myFooter">
            <div style="text-align: center; font-size: 9px; color: #64748b;">
                Página {PAGENO} de {nbpg}
            </div>
        </htmlpagefooter>
        ';

        // Capa do Relatório (sempre exibida na página 1)
        $html .= '
        <div class="cover-container" style="page: cover;">
            <div style="margin-top: 50px;">
                <div style="font-size: 48px; color: #8b5cf6; font-weight: bold;">SH</div>
                <div class="cover-title">Soundhaven — Relatório da Coleção</div>
                <div class="cover-subtitle">Curadoria e organização detalhada do acervo musical</div>
            </div>
            
            <div class="cover-box">
                <h3>Resumo do Relatório</h3>
                <div class="cover-meta-item">
                    <span class="cover-meta-label">Filtros Aplicados:</span> ' . htmlspecialchars($resumoFiltros ?: 'Nenhum filtro aplicado (Todos os itens)') . '
                </div>
                <div class="cover-meta-item">
                    <span class="cover-meta-label">Total de Itens Encontrados:</span> ' . count($itens) . ' mídias
                </div>
                <div class="cover-meta-item">
                    <span class="cover-meta-label">Data de Emissão:</span> ' . date('d/m/Y H:i:s') . '
                </div>
            </div>
        </div>
        <pagebreak />
        ';

        $html .= '
        <table>
            <thead>
                <tr>
        ';

        if ($incluir_capas_albuns) {
            $html .= '      <th style="width: 8%; text-align: center;">Capa</th>';
        }

        $html .= '
                    <th style="width: ' . ($incluir_capas_albuns ? '18%' : '20%') . ';">Artista</th>
                    <th style="width: ' . ($incluir_capas_albuns ? '18%' : '20%') . ';">Álbum</th>
                    <th style="width: 8%; text-align: center;">Ano</th>
                    <th style="width: ' . ($incluir_capas_albuns ? '18%' : '20%') . ';">Gênero / Estilo</th>
                    <th style="width: 10%; text-align: center;">Formato</th>
                    <th style="width: ' . ($incluir_capas_albuns ? '10%' : '12%') . ';">Gravadora</th>
                    <th style="width: 10%; text-align: center;">Data de Compra</th>
                </tr>
            </thead>
            <tbody>
        ';

        if (empty($itens)) {
            $colspan = $incluir_capas_albuns ? 8 : 7;
            $html .= '
                <tr>
                    <td colspan="' . $colspan . '" style="text-align: center; color: #64748b; padding: 20px;">
                        Nenhuma mídia encontrada com os filtros selecionados.
                    </td>
                </tr>
            ';
        } else {
            foreach ($itens as $item) {
                $generoEstilo = [];
                if (!empty($item['generos'])) {
                    $generoEstilo[] = $item['generos'];
                }
                if (!empty($item['estilos'])) {
                    $generoEstilo[] = $item['estilos'];
                }
                $geStr = implode(', ', $generoEstilo);
                
                $dataAquisicao = !empty($item['midia_data_aquisicao']) 
                    ? date('d/m/Y', strtotime($item['midia_data_aquisicao'])) 
                    : 'N/D';
                    
                $dataLancamento = !empty($item['album_data_lancamento'])
                    ? date('Y', strtotime($item['album_data_lancamento']))
                    : 'N/D';

                $html .= '
                <tr>
                ';

                if ($incluir_capas_albuns) {
                    $capa = $item['album_capa_url'];
                    if (empty($capa)) {
                        $capaPath = '/var/www/html/public/assets/images/placeholder.jpg';
                    } elseif (strpos($capa, 'http://') === 0 || strpos($capa, 'https://') === 0) {
                        $capaPath = $capa;
                    } else {
                        $capaPath = '/var/www/html/public/' . ltrim($capa, '/');
                    }
                    $html .= '
                    <td style="text-align: center; vertical-align: middle;">
                        <img src="' . htmlspecialchars($capaPath) . '" style="width: 32px; height: 32px; object-fit: cover; border-radius: 4px;" />
                    </td>
                    ';
                }

                $html .= '
                    <td><strong>' . htmlspecialchars($item['artista_nome']) . '</strong></td>
                    <td>' . htmlspecialchars($item['album_titulo']) . '</td>
                    <td style="text-align: center;">' . htmlspecialchars($dataLancamento) . '</td>
                    <td>' . htmlspecialchars($geStr ?: 'N/D') . '</td>
                    <td style="text-align: center;">' . htmlspecialchars($item['formato_nome']) . '</td>
                    <td>' . htmlspecialchars($item['gravadora_nome']) . '</td>
                    <td style="text-align: center;">' . htmlspecialchars($dataAquisicao) . '</td>
                </tr>
                ';
            }
        }

        $html .= '
            </tbody>
        </table>
        </body>
        </html>
        ';

        // 9. Inicializar o mPDF e imprimir
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 15,
            'margin_bottom' => 20,
            'margin_header' => 5,
            'margin_footer' => 10,
            'tempDir' => '/tmp',
        ]);

        $mpdf->WriteHTML($html);
        $mpdf->Output('Relatorio_Soundhaven.pdf', \Mpdf\Output\Destination::INLINE);
        exit;
    }
}
