<?php

namespace App\Repositories;

use App\DTOs\RegisterDTO;
use App\DTOs\UpdatePerfilDTO;
use App\Models\Utilizador;
use App\Repositories\Interfaces\AuthRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class AuthRepository implements AuthRepositoryInterface
{
    // método retorna o objeto Utilizador
    public function register(RegisterDTO $dto): Utilizador
    {
        return Utilizador::create([
            'nome'     => $dto->nome,
            'username' => $dto->username,
            'email'    => $dto->email,
            'password' => $dto->password,
        ]);
    }

    // método, procure o primeiro Utilizador com esse email e retorne-o ou não (?)
    public function findByEmail(string $email): ?Utilizador
    {
        return Utilizador::where('email', $email)->first();
    }

    public function findById(int $id): ?Utilizador
    {
        return Utilizador::find($id);
    }

    public function updatePerfil(Utilizador $utilizador, UpdatePerfilDTO $dto): Utilizador // $dto contém os novos dados enviados na requisição
    {
        $dados = array_filter([
            'nome'           => $dto->nome,
            'username'       => $dto->username,
            'bio'            => $dto->bio,
            'perfil_privado' => $dto->perfil_privado,
        ], fn($value) => !is_null($value)); // função anónima que remove do array tudo que for null

        $utilizador->update($dados); // utilizador recebe os novos dados atualizados no banco de dados

        return $utilizador->fresh(); // recarrega o objeto no banco de dados
    }

    public function updateFotoPerfil(Utilizador $utilizador, string $path): Utilizador
    {
        // Apaga a foto antiga se existir
        if ($utilizador->foto_perfil) {
            Storage::disk('public')->delete($utilizador->foto_perfil);
        }

        $utilizador->update(['foto_perfil' => $path]);

        return $utilizador->fresh();
    }
}