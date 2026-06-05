<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Notificacao;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class NotificacaoController extends Controller
{
    public function index(): JsonResponse
    {
        $notificacoes = Notificacao::query()
            ->with(['utilizador:id,nome,username,foto_perfil'])
            ->where('utilizador_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'notificacoes' => $notificacoes,
        ]);
    }

    public function marcarComoLida(int $id): JsonResponse
    {
        $notificacao = Notificacao::query()
            ->where('utilizador_id', Auth::id())
            ->find($id);

        if (!$notificacao) {
            return response()->json([
                'message' => 'Notificação não encontrada.',
            ], 404);
        }

        $notificacao->update(['lida' => true]);

        return response()->json([
            'message' => 'Notificação marcada como lida.',
            'notificacao' => $notificacao->fresh(['utilizador:id,nome,username,foto_perfil']),
        ]);
    }
}
