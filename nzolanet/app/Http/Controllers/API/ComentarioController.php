<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comentario\CreateComentarioRequest;
use App\Http\Requests\Comentario\UpdateComentarioRequest;
use App\Services\ComentarioService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\API\ComentarioController;

class ComentarioController extends Controller
{
    public function __construct(
        private ComentarioService $service
    ) {}

    // GET /api/publicacoes/{publicacao_id}/comentarios
    public function index(int $publicacao_id): JsonResponse
    {
        $comentarios = $this->service->listarPorPublicacao($publicacao_id);

        return response()->json([
            'success' => true,
            'data'    => $comentarios,
        ], 200);
    }

    // POST /api/publicacoes/{publicacao_id}/comentarios
    public function store(CreateComentarioRequest $request, int $publicacao_id): JsonResponse
    {
        $utilizador_id = auth()->id();

        $comentario = $this->service->criar(
            $request->validated(),
            $publicacao_id,
            $utilizador_id
        );

        return response()->json([
            'success' => true,
            'message' => 'Comentário criado com sucesso.',
            'data'    => $comentario,
        ], 201);
    }

    // PUT /api/comentarios/{id}
    public function update(UpdateComentarioRequest $request, int $id): JsonResponse
    {
        $utilizador_id = auth()->id();

        $resultado = $this->service->actualizar(
            $id,
            $request->validated(),
            $utilizador_id
        );

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

    // DELETE /api/comentarios/{id}
    public function destroy(int $id): JsonResponse
    {
        $utilizador_id = auth()->id();

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