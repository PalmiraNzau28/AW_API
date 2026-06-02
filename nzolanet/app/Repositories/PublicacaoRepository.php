<?php

namespace App\Repositories;

use App\DTOs\PublicacaoDTO;
use App\DTOs\UpdatePublicacaoDTO;
use App\Models\Publicacao;
use App\Repositories\Interfaces\PublicacaoRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;

// Isto mantém o código organizado: BD ↔ Repository ↔ Service ↔ Controller ↔ HTTP

class PublicacaoRepository implements PublicacaoRepositoryInterface
{
  public function create(PublicacaoDTO $dto): Publicacao
  {
    return Publicacao::create([
      'utilizador_id' => $dto->utilizador_id,
      'texto' => $dto->texto,
      'imagem' => $dto->imagem, // Path do ficheiro em storage/app/public/publicacoes/
      'video' => $dto->video,
    ]);
  }

  public function getAll(): Collection
  {
    // with('utilizador') → eager loading: carrega o autor de cada publicação

    return Publicacao::with('utilizador')
      ->withCount(['bazes', 'comentarios'])
      ->latest()
      ->get();
  }

  public function findById(int $id): ?Publicacao
  {
    // Carrega também o autor e as contagens para a visualização individual
    return Publicacao::with('utilizador')
      ->withCount(['bazes', 'comentarios'])
      ->find($id); // find() devolve null se não encontrar (ao contrário de findOrFail que lança exceção)
  }

  public function update(Publicacao $publicacao, UpdatePublicacaoDTO $dto): Publicacao
  {
    // array_filter com fn($v) => !is_null($v) → remove os campos que vieram como null
    // do DTO. Assim, só atualizamos na BD os campos que o utilizador realmente enviou.
    // Exemplo: se só enviou 'texto', só 'texto' é atualizado — imagem e vídeo mantêm-se.
    $dados = array_filter([
      'texto' => $dto->texto,
      'imagem' => $dto->imagem,
      'video' => $dto->video,
    ], fn($value) => !is_null($value));

    $publicacao->update($dados);

    // fresh() recarrega o objeto da BD, garantindo que devolvemos
    // os dados mais recentes (incluindo timestamps atualizados).
    return $publicacao->fresh();
  }

  public function delete(Publicacao $publicacao): void
  {
    // Apaga os ficheiros de media associados antes de eliminar o registo.
    // Se não fizéssemos isto, os ficheiros ficariam "órfãos" no storage.
    if ($publicacao->imagem) {
      Storage::disk('public')->delete($publicacao->imagem);
    }

    if ($publicacao->video) {
      Storage::disk('public')->delete($publicacao->video);
    }

    // A migration tem onDelete('cascade'), por isso os comentários
    // e bazes desta publicação são apagados automaticamente pela BD.
    $publicacao->delete();
  }
}
