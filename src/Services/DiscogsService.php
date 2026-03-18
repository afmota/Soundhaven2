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
     * Busca o álbum e retorna o ID do Discogs e a Tracklist
     */
    public function buscarFaixas($catalogo, $titulo = '') {
        // 1. Limpeza de catálogo para fallback (remove espaços e traços)
        $catalogoLimpo = preg_replace('/[^a-zA-Z0-9]/', '', $catalogo);
        $tituloEncoded = urlencode($titulo);

        // 2. Estratégia de busca em cascata (Agressiva)
        // Tentamos primeiro a busca global 'q', que é a mais eficaz do Discogs
        $tentativas = [
            "https://api.discogs.com/database/search?q=" . urlencode($catalogo) . "&type=release",
            "https://api.discogs.com/database/search?q={$catalogoLimpo}&release_title={$tituloEncoded}&type=release",
            "https://api.discogs.com/database/search?catno=" . urlencode($catalogo) . "&type=release"
        ];

        foreach ($tentativas as $url) {
            $data = $this->request($url);

            if ($data && !empty($data['results'])) {
                // Pegamos o ID do primeiro resultado (Release ID)
                $releaseId = $data['results'][0]['id'];
                return $this->getReleaseDetails($releaseId);
            }
        }

        return null;
    }

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

    /**
     * Realiza a requisição usando cURL (mais estável que file_get_contents para APIs externas)
     */
    private function request($url) {
        $ch = curl_init();
        
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => $this->userAgent,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_SSL_VERIFYPEER => false, // Evita erros de SSL em ambiente local (XAMPP/WAMP)
            CURLOPT_HTTPHEADER => [
                "Authorization: Discogs token=" . $this->token,
                "Accept: application/json"
            ]
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            return null;
        }

        return json_decode($response, true);
    }
}