<?php

namespace App\DTOs;

class ComentarioDTO
{
    public function __construct(
        public readonly ?int $id,
        public readonly int $publicacao_id,
        public readonly int $utilizador_id,
        public readonly string $texto,
        public readonly ?string $created_at = null,
        public readonly ?string $autor_nome = null,
        public readonly ?string $autor_foto = null,
    ) {}

    // Converte os dados da request (entrada) para o DTO
    public static function fromRequest(array $data, int $publicacao_id, int $utilizador_id): self
    {
        return new self(
            id: null,
            publicacao_id: $publicacao_id,
            utilizador_id: $utilizador_id,
            texto: $data['texto'],
        );
    }

    // Converte um Model (base de dados) para o DTO
    public static function fromModel($comentario): self
    {
        return new self(
            id: $comentario->id,
            publicacao_id: $comentario->publicacao_id,
            utilizador_id: $comentario->utilizador_id,
            texto: $comentario->texto,
            created_at: $comentario->created_at?->format('d/m/Y H:i'),
            autor_nome: $comentario->utilizador?->nome,
            autor_foto: $comentario->utilizador?->foto_perfil,
        );
    }

    // Converte o DTO para array (para enviar como JSON)
    public function toArray(): array
    {
        return [
            'id'            => $this->id,
            'publicacao_id' => $this->publicacao_id,
            'utilizador_id' => $this->utilizador_id,
            'texto'         => $this->texto,
            'created_at'    => $this->created_at,
            'autor_nome'    => $this->autor_nome,
            'autor_foto'    => $this->autor_foto,
        ];
    }
}