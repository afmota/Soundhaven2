<?php
namespace App\Core\Entities;

use DateTime;

class Album {
    public function __construct(
        private string $titulo,
        private ?string $capa_url,
        private int $artista_id,
        private string $data_lancamento,
        private int $tipo_id,
        private int $situacao,
        private string $artistaNome
    ) {}

    // Novo método adicionado para resolver o erro:
    public function getArtistaId(): int {
        return $this->artista_id;
    }

    public function getTitulo(): string { return htmlspecialchars($this->titulo); }
    public function getArtistaNome(): string { return htmlspecialchars($this->artistaNome); }
    
    public function getCapaUrl(): string {
        return $this->capa_url ?: 'https://placehold.co/300x300?text=Sem+Capa';
    }

    public function getAnoLancamento(): string {
        $date = new DateTime($this->data_lancamento);
        return $date->format('Y');
    }

    public function getBadgeSituacao(): string {
        // Como agora filtramos NOT IN (4,5), você pode personalizar essa lógica
        return "<span class='badge bg-dark border border-secondary text-muted'>ID: {$this->artista_id}</span>";
    }
}