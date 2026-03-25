<?php
namespace App\Services;

/**
 * DiscogsService - Motor de busca e importação para o SoundHaven2
 * Integração baseada na lógica de 3 níveis de busca da Versão 1.0
 */
class DiscogsService {
    // Substitua pelo seu token se preferir, mas mantive o que você enviou
    private $token = 'XquypjKpERmGKjMRfgUbbVonxtGjHTggIeFgHxvo';
    private $userAgent = 'SoundHavenApp/2.0 (contato@seusite.com)';

    /**
     * Recupera os detalhes específicos de um Release (especialmente a tracklist)
     */
    private function getReleaseDetails($id) {
        $url = "https://api.discogs.com/releases/" . $id;
        $data = $this->request($url);

        if (!$data || empty($data['tracklist'])) {
            return null;
        }

        $faixas = [];
        $seq = 1;

        foreach ($data['tracklist'] as $t) {
            // Filtra para pegar apenas faixas reais (ignora labels de Lado A/B ou vídeos)
            if (($t['type_'] ?? 'track') === 'track') {
                $faixas[] = [
                    'numero' => $seq++,
                    'titulo' => strip_tags($t['title']),
                    'duracao' => $this->formatarDuracao($t['duration'] ?? '')
                ];
            }
        }

        return [
            'discogs_id' => $id,
            'tracklist' => $faixas
        ];
    }

    /**
     * Garante que a duração esteja no formato compatível com o banco (00:00:00)
     */
    private function formatarDuracao($duracao) {
        if (empty($duracao)) return null;
        
        $partes = explode(':', $duracao);
        
        // Se vier MM:SS, transforma em 00:MM:SS
        if (count($partes) == 2) {
            return "00:" . str_pad($partes[0], 2, "0", STR_PAD_LEFT) . ":" . str_pad($partes[1], 2, "0", STR_PAD_LEFT);
        }
        
        return $duracao;
    }

public function buscarFaixas($catalogo, $titulo = '') {
    $catalogoLimpo = preg_replace('/[^a-zA-Z0-9]/', '', $catalogo);
    $tituloEncoded = urlencode($titulo);

    // As 4 tentativas sugeridas, da mais precisa para a mais aberta
    $tentativas = [
        "https://api.discogs.com/database/search?q=" . urlencode($catalogo . " " . $titulo) . "&type=release",
        "https://api.discogs.com/database/search?q=" . urlencode($catalogo) . "&type=release",
        "https://api.discogs.com/database/search?q={$catalogoLimpo}&release_title={$tituloEncoded}&type=release",
        "https://api.discogs.com/database/search?catno=" . urlencode($catalogo) . "&type=release"
    ];

    foreach ($tentativas as $url) {
        $url .= "&token={$this->token}"; // Anexa o token na URL para garantir
        $data = $this->request($url);

        if ($data && !empty($data['results'])) {
            // --- AQUI ENTRA A INTELIGÊNCIA DO LOOP ---
            foreach ($data['results'] as $result) {
                if (isset($result['id'])) {
                    $details = $this->getReleaseDetails($result['id']);
                    
                    // Só aceitamos se o release tiver faixas!
                    if ($details && !empty($details['tracklist'])) {
                        return $details; // Vitória! Encontramos um release completo.
                    }
                }
            }
        }
    }
    return null;
}

private function request($url) {
    usleep(500000);
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_USERAGENT => $this->userAgent,
        CURLOPT_TIMEOUT => 20,
        CURLOPT_SSL_VERIFYPEER => false
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Log de erro para você não ficar no escuro
    if ($httpCode !== 200) {
        error_log("SoundHaven Discogs Error: HTTP $httpCode na URL $url");
        return null;
    }

    return json_decode($response, true);
}
}