<?php

namespace App\Repositories;

use App\DTOs\PublicacaoDTO;
use App\DTOs\UpdatePublicacaoDTO;
use App\Models\Baze;
use App\Models\Comentario;
use App\Models\Publicacao;
use App\Repositories\Interfaces\PublicacaoRepositoryInterface;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class PublicacaoRepository implements PublicacaoRepositoryInterface
{
  public function create(PublicacaoDTO $dto): Publicacao
  {
    return Publicacao::create([
      'utilizador_id' => $dto->utilizador_id,
      'texto' => $dto->texto,
      'imagem' => $dto->imagem, 
      'video' => $dto->video,
    ]);
  }

  public function getAll(int $perPage = 5): Paginator
  {
    $paginator = Publicacao::query()
      ->select(['id', 'utilizador_id', 'texto', 'imagem', 'video', 'created_at', 'updated_at'])
      ->with('utilizador:id,nome,username,foto_perfil')
      ->latest()
      ->simplePaginate($perPage);

    $ids = $paginator->getCollection()->pluck('id');

    if ($ids->isEmpty()) {
      return $paginator;
    }

    $bazes = Baze::query()
      ->selectRaw('publicacao_id, count(*) as total')
      ->whereIn('publicacao_id', $ids)
      ->groupBy('publicacao_id')
      ->pluck('total', 'publicacao_id');

    $comentarios = Comentario::query()
      ->selectRaw('publicacao_id, count(*) as total')
      ->whereIn('publicacao_id', $ids)
      ->groupBy('publicacao_id')
      ->pluck('total', 'publicacao_id');

    $bazados = Auth::check()
      ? Baze::query()
        ->where('utilizador_id', Auth::id())
        ->whereIn('publicacao_id', $ids)
        ->pluck('publicacao_id')
        ->flip()
      : collect();

    $paginator->setCollection(
      $paginator->getCollection()->map(function (Publicacao $publicacao) use ($bazes, $comentarios, $bazados) {
        $publicacao->setAttribute('bazes_count', (int) ($bazes[$publicacao->id] ?? 0));
        $publicacao->setAttribute('comentarios_count', (int) ($comentarios[$publicacao->id] ?? 0));
        $publicacao->setAttribute('bazado', $bazados->has($publicacao->id));

        return $publicacao;
      })
    );

    return $paginator;
  }

  public function findById(int $id): ?Publicacao
  {
    
    return Publicacao::query()
      ->select(['id', 'utilizador_id', 'texto', 'imagem', 'video', 'created_at', 'updated_at'])
      ->with('utilizador:id,nome,username,foto_perfil')
      ->withCount(['bazes', 'comentarios'])
      ->when(Auth::check(), function ($query) {
        $query->withExists([
          'bazes as bazado' => fn($q) => $q->where('utilizador_id', Auth::id()),
        ]);
      })
      ->find($id); 
  }

  public function update(Publicacao $publicacao, UpdatePublicacaoDTO $dto): Publicacao
  {
    
    $dados = array_filter([
      'texto' => $dto->texto,
      'imagem' => $dto->imagem,
      'video' => $dto->video,
    ], fn($value) => !is_null($value));

    $publicacao->update($dados);

    
    return $publicacao->fresh();
  }

  public function delete(Publicacao $publicacao): void
  {
    // Apaga os ficheiros de media associados antes de eliminar o registo.
    
    if ($publicacao->imagem) {
      Storage::disk('public')->delete($publicacao->imagem);
    }

    if ($publicacao->video) {
      Storage::disk('public')->delete($publicacao->video);
    }

    $publicacao->delete();
  }
}
