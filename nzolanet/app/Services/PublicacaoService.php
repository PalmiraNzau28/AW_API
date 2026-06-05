<?php

namespace App\Services;

use App\DTOs\PublicacaoDTO;
use App\DTOs\UpdatePublicacaoDTO;
use App\Models\Publicacao;
use App\Repositories\Interfaces\PublicacaoRepositoryInterface;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class PublicacaoService
{
  public function __construct(
    private readonly PublicacaoRepositoryInterface $publicacaoRepository,
  ) {
  }

  public function criar(array $dadosValidados, ?UploadedFile $imagem, ?UploadedFile $video): Publicacao
  {
    // Regra: apenas utilizadores autenticados podem criar publicações.
    $utilizadorId = Auth::id();

    // Upload da imagem, se foi enviada
    $pathImagem = null;
    if ($imagem) {
      $pathImagem = $this->guardarFicheiro($imagem, 'publicacoes', 'img');
    }

    // Upload do vídeo, se foi enviado
    $pathVideo = null;
    if ($video) {
      $pathVideo = $this->guardarFicheiro($video, 'publicacoes', 'vid');
    }

    // Monta o DTO com todos os dados prontos (incluindo os paths dos ficheiros)
    $dto = PublicacaoDTO::fromArray([
      'utilizador_id' => $utilizadorId,
      'texto' => $dadosValidados['texto'] ?? null,
      'imagem' => $pathImagem,
      'video' => $pathVideo,
    ]);

    return $this->publicacaoRepository->create($dto);
  }

  public function listar(int $perPage = 5): Paginator
  {
    
    return $this->publicacaoRepository->getAll($perPage);
  }

  public function ver(int $id): ?Publicacao
  {
    return $this->publicacaoRepository->findById($id);
  }

  public function atualizar(int $id, array $dadosValidados, ?UploadedFile $imagem, ?UploadedFile $video): array
  {
    $publicacao = $this->publicacaoRepository->findById($id);

    // Verifica se a publicação existe
    if (!$publicacao) {
      return ['erro' => 'not_found'];
    }

    // Regra de negócio: só o autor pode editar a sua publicação.

    if ($publicacao->utilizador_id !== Auth::id()) {
      return ['erro' => 'forbidden'];
    }

    // Upload da nova imagem, se foi enviada

    $pathImagem = null;
    if ($imagem) {

      if ($publicacao->imagem) {
        \Illuminate\Support\Facades\Storage::disk('public')->delete($publicacao->imagem);
      }
      $pathImagem = $this->guardarFicheiro($imagem, 'publicacoes', 'img');
    }

    // Upload do novo vídeo, se foi enviado
    $pathVideo = null;
    if ($video) {
      if ($publicacao->video) {
        \Illuminate\Support\Facades\Storage::disk('public')->delete($publicacao->video);
      }
      $pathVideo = $this->guardarFicheiro($video, 'publicacoes', 'vid');
    }

    $dto = UpdatePublicacaoDTO::fromArray([
      'texto' => $dadosValidados['texto'] ?? null,
      'imagem' => $pathImagem,
      'video' => $pathVideo,
    ]);

    $publicacaoAtualizada = $this->publicacaoRepository->update($publicacao, $dto);

    return ['publicacao' => $publicacaoAtualizada];
  }

  public function eliminar(int $id): array
  {
    $publicacao = $this->publicacaoRepository->findById($id);

    if (!$publicacao) {
      return ['erro' => 'not_found'];
    }

    // Regra: só o autor pode eliminar a sua publicação
    if ($publicacao->utilizador_id !== Auth::id()) {
      return ['erro' => 'forbidden'];
    }

    
    $this->publicacaoRepository->delete($publicacao);

    return ['sucesso' => true];
  }


  private function guardarFicheiro(UploadedFile $ficheiro, string $pasta, string $prefixo): string
  {
    $nomeUnico = $prefixo . '_' . Auth::id() . '_' . Str::uuid() . '.' . $ficheiro->getClientOriginalExtension();


    return $ficheiro->storeAs($pasta, $nomeUnico, 'public');
  }
}
