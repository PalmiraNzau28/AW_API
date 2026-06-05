<?php

namespace App\DTOs;

class UpdatePublicacaoDTO
{
    public function __construct(
        public readonly ?string $texto,  
        public readonly ?string $imagem, 
        public readonly ?string $video,  
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            texto:  $data['texto']  ?? null,
            imagem: $data['imagem'] ?? null,
            video:  $data['video']  ?? null,
        );
    }
}
