<?php

namespace App\Services;

use App\DTOs\LoginDTO;
use App\DTOs\RegisterDTO;
use App\DTOs\UpdatePerfilDTO;
use App\Models\Utilizador;
use App\Repositories\Interfaces\AuthRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    public function __construct(
        private readonly AuthRepositoryInterface $authRepository,
    ) {}

    public function register(RegisterDTO $dto): array
    {
        $utilizador = $this->authRepository->register($dto);
        $token      = JWTAuth::fromUser($utilizador);

        return [
            'utilizador' => $utilizador,
            'token'      => $token,
        ];
    }

    public function login(LoginDTO $dto): ?array
    {
        $credentials = [
            'email'    => $dto->email,
            'password' => $dto->password,
        ];

        $token = Auth::attempt($credentials);

        if (!$token) {
            return null;
        }

        return [
            'utilizador' => Auth::user(),
            'token'      => $token,
        ];
    }

    public function logout(): void
    {
        Auth::logout();
    }

    public function me(): ?Utilizador
    {
        return Auth::user();
    }

    public function refresh(): string
    {
        return Auth::refresh();
    }

    public function updatePerfil(UpdatePerfilDTO $dto): Utilizador
    {
        $utilizador = Auth::user();

        return $this->authRepository->updatePerfil($utilizador, $dto);
    }

    public function updateFotoPerfil(UploadedFile $foto): Utilizador
    {
        $utilizador = Auth::user();

        // Gera um nome único para o ficheiro
        $nomeUnico = 'perfil_' . $utilizador->id . '_' . Str::uuid() . '.' . $foto->getClientOriginalExtension();

        // Guarda o ficheiro na pasta fotos_perfil dentro de storage/app/public
        $path = $foto->storeAs('fotos_perfil', $nomeUnico, 'public');

        return $this->authRepository->updateFotoPerfil($utilizador, $path);
    }
}