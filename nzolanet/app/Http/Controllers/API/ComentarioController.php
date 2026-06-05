<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\ComentarioService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComentarioController extends Controller
{
    public function __construct(
        private ComentarioService $service
    ) {
    }

    public function index(int $publicacao_id): JsonResponse
    {
        $comentarios = $this->service->listarPorPublicacao($publicacao_id);
        return response()->json([
            'comentarios' => $comentarios,
        ], 200);
    }

    public function store(Request $request, int $publicacao_id): JsonResponse
    {
        $dados = $request->validate([
            'texto' => 'required|string|min:1|max:500',
        ]);

        $comentario = $this->service->criar($dados, $publicacao_id, (int) Auth::id());
        return response()->json([
            'message' => 'Comentário criado com sucesso.',
            'comentario' => $comentario,
        ], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $dados = $request->validate([
            'texto' => 'required|string|min:1|max:500',
        ]);

        $resultado = $this->service->actualizar($id, $dados, (int) Auth::id());

        if ($resultado === 'nao_encontrado') {
            return response()->json([
                'message' => 'Comentário não encontrado.',
            ], 404);
        }

        if ($resultado === 'sem_permissao') {
            return response()->json([
                'message' => 'Não tens permissão para editar este comentário.',
            ], 403);
        }

        return response()->json([
            'message' => 'Comentário actualizado com sucesso.',
            'comentario' => $resultado,
        ], 200);
    }

    public function destroy(int $id): JsonResponse
    {
        $resultado = $this->service->apagar($id, (int) Auth::id());

        if ($resultado === 'nao_encontrado') {
            return response()->json([
                'message' => 'Comentário não encontrado.',
            ], 404);
        }

        if ($resultado === 'sem_permissao') {
            return response()->json([
                'message' => 'Não tens permissão para apagar este comentário.',
            ], 403);
        }

        return response()->json([
            'message' => 'Comentário apagado com sucesso.',
        ], 200);
    }
}
