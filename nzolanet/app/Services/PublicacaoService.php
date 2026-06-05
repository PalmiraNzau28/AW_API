<?php

namespace App\Services;

use App\DTOs\PublicacaoDTO;
use App\DTOs\UpdatePublicacaoDTO;
use App\Models\Publicacao;
use App\Repositories\Interfaces\PublicacaoRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

// O Service é onde vivem as regras de negócio da aplicação.
// Ele coordena o fluxo: recebe dados do Controller, aplica as regras,
// faz uploads se necessário, e delega a persistência ao Repository.
//
// Fluxo de um pedido HTTP:
//   Request → Controller → Service → Repository → BD
//
// O Controller não conhece a BD. O Repository não conhece regras de negócio.
// O Service é a "cola" entre os dois.

class PublicacaoService
{
  public function __construct(
    private readonly PublicacaoRepositoryInterface $publicacaoRepository,
  ) {
  }

  public function criar(array $dadosValidados, ?UploadedFile $imagem, ?UploadedFile $video): Publicacao
  {
    // Regra: apenas utilizadores autenticados podem criar publicações.
    // Garantido pelo middleware 'auth:api' na rota, mas o Service
    // obtém o ID do utilizador autenticado via Auth::id().
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

  public function listar(): Collection
  {
    // Delega ao Repository — o Service não precisa de saber como a query é feita
    return $this->publicacaoRepository->getAll();
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
    // Comparamos o ID do utilizador autenticado com o utilizador_id da publicação.
    // Se forem diferentes → erro 403 Forbidden.
    if ($publicacao->utilizador_id !== Auth::id()) {
      return ['erro' => 'forbidden'];
    }

    // Upload da nova imagem, se foi enviada
    // Nota: o Repository apaga a imagem antiga quando guarda a nova
    $pathImagem = null;
    if ($imagem) {
      // Apaga a imagem antiga antes de guardar a nova
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

    // O Repository trata também de apagar os ficheiros do storage
    $this->publicacaoRepository->delete($publicacao);

    return ['sucesso' => true];
  }

  // ─── Método privado auxiliar para upload de ficheiros ─────────────────

  // Gera um nome único para o ficheiro e guarda-o no storage.
  // Usamos Str::uuid() para garantir que nunca há conflito de nomes,
  // mesmo que dois utilizadores façam upload do mesmo ficheiro ao mesmo tempo.

  private function guardarFicheiro(UploadedFile $ficheiro, string $pasta, string $prefixo): string
  {
    $nomeUnico = $prefixo . '_' . Auth::id() . '_' . Str::uuid() . '.' . $ficheiro->getClientOriginalExtension();

    // storeAs($pasta, $nome, 'public') guarda em storage/app/public/$pasta/$nome
    // e devolve o path relativo (ex: publicacoes/img_5_uuid.jpg)
    return $ficheiro->storeAs($pasta, $nomeUnico, 'public');
  }
}
