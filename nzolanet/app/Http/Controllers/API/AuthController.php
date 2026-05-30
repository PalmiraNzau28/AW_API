<?php

namespace App\Http\Controllers\API;

use App\DTOs\LoginDTO; // Login há dados transferidos
use App\DTOs\RegisterDTO;
use App\DTOs\UpdatePerfilDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest; // Para Requisições HTML pelo Login
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\UpdateFotoPerfilRequest;
use App\Http\Requests\Auth\UpdatePerfilRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService,
    ) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        $dto    = RegisterDTO::fromArray($request->validated());
        $result = $this->authService->register($dto);

        return response()->json([
            'message'    => 'Utilizador registado com sucesso.',
            'utilizador' => $result['utilizador'],
            'token'      => $result['token'],
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $dto    = LoginDTO::fromArray($request->validated());
        $result = $this->authService->login($dto);

        if (!$result) {
            return response()->json([
                'message' => 'Credenciais inválidas.',
            ], 401);
        }

        return response()->json([
            'message'    => 'Login efectuado com sucesso.',
            'utilizador' => $result['utilizador'],
            'token'      => $result['token'],
        ], 200);
    }

    public function logout(): JsonResponse
    {
        $this->authService->logout();

        return response()->json([
            'message' => 'Sessão terminada com sucesso.',
        ], 200);
    }

    public function me(): JsonResponse
    {
        $utilizador = $this->authService->me();

        return response()->json([
            'utilizador' => $utilizador,
        ], 200);
    }

    public function refresh(): JsonResponse
    {
        $token = $this->authService->refresh();

        return response()->json([
            'token' => $token,
        ], 200);
    }

    public function updatePerfil(UpdatePerfilRequest $request): JsonResponse
    {
        $dto        = UpdatePerfilDTO::fromArray($request->validated());
        $utilizador = $this->authService->updatePerfil($dto);

        return response()->json([
            'message'    => 'Perfil actualizado com sucesso.',
            'utilizador' => $utilizador,
        ], 200);
    }

    public function updateFotoPerfil(UpdateFotoPerfilRequest $request): JsonResponse
    {
        $utilizador = $this->authService->updateFotoPerfil($request->file('foto_perfil'));

        return response()->json([
            'message'    => 'Foto de perfil actualizada com sucesso.',
            'utilizador' => $utilizador,
        ], 200);
    }
}