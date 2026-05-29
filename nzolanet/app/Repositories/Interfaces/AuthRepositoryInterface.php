<?php

namespace App\Repositories\Interfaces;

use App\DTOs\RegisterDTO;
use App\DTOs\UpdatePerfilDTO;
use App\Models\Utilizador;

// Definição dos métodos que o Repository (AuthRepository.php) deve ter
interface AuthRepositoryInterface
{
    public function register(RegisterDTO $dto): Utilizador;
    public function findByEmail(string $email): ?Utilizador;
    public function findById(int $id): ?Utilizador;
    public function updatePerfil(Utilizador $utilizador, UpdatePerfilDTO $dto): Utilizador;
    public function updateFotoPerfil(Utilizador $utilizador, string $path): Utilizador;
}