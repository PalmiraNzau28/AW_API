<?php

namespace App\Http\Controllers;

use App\Services\ComentarioService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ComentarioController extends Controller
{
    public function __construct(
        private ComentarioService $service
    ) {}

    public function index(int $publicacao_id): JsonResponse
    {
        $comentarios = $this->service->listarPorPublicacao($publicacao_id);

        return response()->json([
            'success' => true,
            'data'    => $comentarios,
        ], 200);
    }

    public function store(Request $request, int $publicacao_id): JsonResponse
    {
        $dados = $request->validate([
            'texto'         => 'required|string|min:1|max:500',
            'utilizador_id' => 'required|integer|exists:utilizadores,id',
        ]);

        $comentario = $this->service->criar($dados, $publicacao_id, $dados['utilizador_id']);

        return response()->json([
            'success' => true,
            'message' => 'Comentário criado com sucesso.',
            'data'    => $comentario,
        ], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $dados = $request->validate([
            'texto'         => 'required|string|min:1|max:500',
            'utilizador_id' => 'required|integer|exists:utilizadores,id',
        ]);

        $resultado = $this->service->actualizar($id, $dados, $dados['utilizador_id']);

        if ($resultado === 'nao_encontrado') {
            return response()->json([
                'success' => false,
                'message' => 'Comentário não encontrado.',
            ], 404);
        }

        if ($resultado === 'sem_permissao') {
            return response()->json([
                'success' => false,
                'message' => 'Não tens permissão para editar este comentário.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Comentário actualizado com sucesso.',
            'data'    => $resultado,
        ], 200);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $utilizador_id = $request->input('utilizador_id');

        $resultado = $this->service->apagar($id, $utilizador_id);

        if ($resultado === 'nao_encontrado') {
            return response()->json([
                'success' => false,
                'message' => 'Comentário não encontrado.',
            ], 404);
        }

        if ($resultado === 'sem_permissao') {
            return response()->json([
                'success' => false,
                'message' => 'Não tens permissão para apagar este comentário.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Comentário apagado com sucesso.',
        ], 200);
    }
}