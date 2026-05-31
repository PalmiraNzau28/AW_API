<?php

namespace App\DTOs;

class UpdateComentarioDTO
{
    public function __construct(
        public readonly string $texto,
    ) {}

    // Converte os dados da request para o DTO
    public static function fromRequest(array $data): self
    {
        return new self(
            texto: $data['texto'],
        );
    }
}