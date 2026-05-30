<?php

namespace App\DTOs;

class UpdatePerfilDTO
{
    public function __construct(
        public readonly ?string $nome,
        public readonly ?string $username,
        public readonly ?string $bio,
        public readonly ?bool   $perfil_privado,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            nome:           $data['nome']           ?? null,
            username:       $data['username']       ?? null,
            bio:            $data['bio']            ?? null,
            perfil_privado: $data['perfil_privado'] ?? null,
        );
    }
}