<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Baze;
use App\Models\Publicacao;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class BazeController extends Controller
{
    public function toggle(int $publicacaoId): JsonResponse
    {
        $publicacao = Publicacao::find($publicacaoId);

        if (!$publicacao) {
            return response()->json(['message' => 'Publicação não encontrada.'], 404);
        }

        $baze = Baze::query()
            ->where('publicacao_id', $publicacaoId)
            ->where('utilizador_id', Auth::id())
            ->first();

        if ($baze) {
            $baze->delete();

            return response()->json([
                'message' => 'Baze removido.',
                'bazado' => false,
                'bazes_count' => Baze::where('publicacao_id', $publicacaoId)->count(),
            ]);
        }

        Baze::create([
            'publicacao_id' => $publicacaoId,
            'utilizador_id' => Auth::id(),
        ]);

        return response()->json([
            'message' => 'Baze adicionado.',
            'bazado' => true,
            'bazes_count' => Baze::where('publicacao_id', $publicacaoId)->count(),
        ], 201);
    }
}
