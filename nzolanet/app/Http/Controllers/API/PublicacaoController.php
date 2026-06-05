<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Publicacao\CreatePublicacaoRequest;
use App\Http\Requests\Publicacao\UpdatePublicacaoRequest;
use App\Services\PublicacaoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;




class PublicacaoController extends Controller
{
  public function __construct(
    private readonly PublicacaoService $publicacaoService,
  ) {
  }

  // Cria uma nova publicação. Apenas utilizadores autenticados .
  public function store(CreatePublicacaoRequest $request): JsonResponse
  {
    
    $publicacao = $this->publicacaoService->criar(
      dadosValidados: $request->validated(),
      imagem: $request->file('imagem'),
      video: $request->file('video'),
    );

    return response()->json([
      'message' => 'Publicação criada com sucesso.',
      'publicacao' => $publicacao,
    ], 201); // 201 Created — indica que um novo recurso foi criado
  }


  // Feed público — lista todas as publicações do mais recente para o mais antigo.
  public function index(Request $request): JsonResponse
  {
    $perPage = min(max((int) $request->query('per_page', 5), 1), 10);
    $publicacoes = $this->publicacaoService->listar($perPage);

    return response()->json([
      'publicacoes' => $publicacoes->items(),
      'meta' => [
        'current_page' => $publicacoes->currentPage(),
        'per_page' => $publicacoes->perPage(),
        'has_more' => $publicacoes->hasMorePages(),
      ],
    ], 200);
  }


 
  public function show(int $id): JsonResponse
  {
    $publicacao = $this->publicacaoService->ver($id);

    if (!$publicacao) {
      return response()->json([
        'message' => 'Publicação não encontrada.',
      ], 404);
    }

    return response()->json([
      'publicacao' => $publicacao,
    ], 200);
  }

  // Edita uma publicação. Só o autor pode editar (verificado no Service).
  public function update(UpdatePublicacaoRequest $request, int $id): JsonResponse
  {
    $resultado = $this->publicacaoService->atualizar(
      id: $id,
      dadosValidados: $request->validated(),
      imagem: $request->file('imagem'),
      video: $request->file('video'),
    );

    if (isset($resultado['erro'])) {
      return match ($resultado['erro']) {
        'not_found' => response()->json(['message' => 'Publicação não encontrada.'], 404),
        'forbidden' => response()->json(['message' => 'Não tens permissão para editar esta publicação.'], 403),
        default => response()->json(['message' => 'Erro inesperado.'], 500),
      };
    }

    return response()->json([
      'message' => 'Publicação actualizada com sucesso.',
      'publicacao' => $resultado['publicacao'],
    ], 200);
  }


  // Elimina uma publicação. Só o autor pode eliminar (verificado no Service).
  public function destroy(int $id): JsonResponse
  {
    $resultado = $this->publicacaoService->eliminar($id);

    if (isset($resultado['erro'])) {
      return match ($resultado['erro']) {
        'not_found' => response()->json(['message' => 'Publicação não encontrada.'], 404),
        'forbidden' => response()->json(['message' => 'Não tens permissão para eliminar esta publicação.'], 403),
        default => response()->json(['message' => 'Erro inesperado.'], 500),
      };
    }

    return response()->json([
      'message' => 'Publicação eliminada com sucesso.',
    ], 200);
  }
}
