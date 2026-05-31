<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\SeguidorService;
use Illuminate\Http\JsonResponse;

class SeguidorController extends Controller
{
    public function __construct(
        private readonly SeguidorService $seguidorService,
    ) {}

    public function seguir(int $id): JsonResponse
    {
        $resultado = $this->seguidorService->seguir($id);

        $codigo = $resultado['sucesso'] ? 200 : 422;

        return response()->json([
            'message' => $resultado['mensagem'],
        ], $codigo);
    }

    public function deixarSeguir(int $id): JsonResponse
    {
        $resultado = $this->seguidorService->deixarSeguir($id);

        $codigo = $resultado['sucesso'] ? 200 : 422;

        return response()->json([
            'message' => $resultado['mensagem'],
        ], $codigo);
    }

    public function seguidores(int $id): JsonResponse
    {
        $seguidores = $this->seguidorService->seguidores($id);

        return response()->json([
            'seguidores' => $seguidores,
            'total'      => $seguidores->count(),
        ], 200);
    }

    public function seguindo(int $id): JsonResponse
    {
        $seguindo = $this->seguidorService->seguindo($id);

        return response()->json([
            'seguindo' => $seguindo,
            'total'    => $seguindo->count(),
        ], 200);
    }
}