<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Utilizador;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UtilizadorController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $dados = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        $termo = trim($dados['q'] ?? '');

        if ($termo === '') {
            return response()->json([
                'utilizadores' => [],
            ]);
        }

        $utilizadores = Utilizador::query()
            ->select(['id', 'nome', 'username', 'foto_perfil', 'bio'])
            ->where('id', '!=', Auth::id())
            ->withExists([
                'seguidores as seguindo' => fn($q) => $q->where('seguidor_id', Auth::id()),
            ])
            ->where(function ($query) use ($termo) {
                $query->where('nome', 'like', '%' . $termo . '%')
                    ->orWhere('username', 'like', '%' . $termo . '%')
                    ->orWhere('email', 'like', '%' . $termo . '%');
            })
            ->orderBy('username')
            ->limit(10)
            ->get();

        return response()->json([
            'utilizadores' => $utilizadores,
        ]);
    }
}
