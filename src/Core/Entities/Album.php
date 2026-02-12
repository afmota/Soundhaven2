<?php

namespace App\Core\Entities;

use DateTime;

/**
 * Entidade Album
 * Representa um registro de álbum com métodos de acesso seguros.
 */
class Album {
    public function __construct(
        private int $id,
        private string $titulo,
        private ?string $capa_url,
        private int $artista_id,
        private string $data_lancamento,
        private int $tipo_id,
        private int $situacao,
        private string $artistaNome,
        private string $data_criacao
    ) {}

    // --- Getters de Identificação ---

    public function getId(): int {
        return $this->id;
    }

    public function getArtistaId(): int {
        return $this->artista_id;
    }

    public function getTitulo(): string { 
        return htmlspecialchars($this->titulo); 
    }

    public function getArtistaNome(): string { 
        return htmlspecialchars($this->artistaNome); 
    }

    // --- Getters de Mídia ---
    
    public function getCapaUrl(): string {
        return $this->capa_url ?: 'https://placehold.co/300x300?text=Sem+Capa';
    }

    // --- Getters de Data ---

    public function getDataLancamento(): string {
        return $this->data_lancamento;
    }

    public function getAnoLancamento(): string {
        $date = new DateTime($this->data_lancamento);
        return $date->format('Y');
    }

    public function getDataCriacao(): string {
        return $this->data_criacao;
    }

    // --- Getters de Estado e Categoria ---

    public function getTipo(): int {
        return $this->tipo_id;
    }

    public function getSituacao(): int {
        return $this->situacao;
    }

    // --- Métodos de Apresentação ---

    public function getBadgeSituacao(): string {
        return "<span class='badge bg-dark border border-secondary text-muted'>ID: {$this->id}</span>";
    }
}