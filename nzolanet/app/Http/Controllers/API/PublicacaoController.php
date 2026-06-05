<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Publicacao\CreatePublicacaoRequest;
use App\Http\Requests\Publicacao\UpdatePublicacaoRequest;
use App\Services\PublicacaoService;
use Illuminate\Http\JsonResponse;




class PublicacaoController extends Controller
{
  public function __construct(
    private readonly PublicacaoService $publicacaoService,
  ) {
  }

  // Cria uma nova publicação. Apenas utilizadores autenticados (middleware na rota).
  public function store(CreatePublicacaoRequest $request): JsonResponse
  {
    // $request->validated() devolve apenas os campos que passaram nas rules().
    // $request->file('imagem') devolve o ficheiro uploaded, ou null se não foi enviado.
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

  // GET /api/publicacoes
  // Feed público — lista todas as publicações do mais recente para o mais antigo.
  public function index(): JsonResponse
  {
    $publicacoes = $this->publicacaoService->listar();

    return response()->json([
      'publicacoes' => $publicacoes,
    ], 200);
  }

  // GET /api/publicacoes/{id}
  // Mostra uma publicação específica com os dados do autor, bazes e comentários.
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

  // PUT /api/publicacoes/{id}
  // Edita uma publicação. Só o autor pode editar (verificado no Service).
  public function update(UpdatePublicacaoRequest $request, int $id): JsonResponse
  {
    $resultado = $this->publicacaoService->atualizar(
      id: $id,
      dadosValidados: $request->validated(),
      imagem: $request->file('imagem'),
      video: $request->file('video'),
    );

    // O Service devolve um array com 'erro' ou 'publicacao'.
    // O Controller interpreta esse resultado e devolve o HTTP correto.
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

  // DELETE /api/publicacoes/{id}
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
