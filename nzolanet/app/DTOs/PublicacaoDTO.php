<?php

namespace App\DTOs;
use Illuminate\Http\UploadedFile;

class PublicacaoDTO
{

  public function __construct(
    public readonly int $utilizador_id,
    public readonly string $texto,
    public readonly ?UploadedFile $imagem,
    public readonly ?UploadedFile $video,
  ) {
  }

  // Cria o DTO a partir de um array (normalmente $request->validated() + utilizador_id do Auth)
  public static function fromArray(array $data): self
  {
    return new self(
      utilizador_id: $data['utilizador_id'],
      texto: $data['texto'] ?? null,
      imagem: $data['imagem'] ?? null,
      video: $data['video'] ?? null,
    );
  }
}