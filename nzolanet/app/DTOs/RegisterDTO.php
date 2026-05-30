<?php

namespace App\DTOs;

class RegisterDTO
{
    public function __construct(
        public readonly string $nome, // garante que os dados do DTO não podem ser alterados depois de criados
        public readonly string $username,
        public readonly string $email,
        public readonly string $password,
    ) {}

    // Método que cria DTO a partir de um array (pelos dados do Request)
    public static function fromArray(array $data): self
    {
        return new self(
            nome:     $data['nome'],
            username: $data['username'],
            email:    $data['email'],
            password: $data['password'],
        );
    }
}