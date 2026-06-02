<?php

namespace App\Repositories\Interfaces;

use App\DTOs\PublicacaoDTO;
use App\DTOs\UpdatePublicacaoDTO;
use App\Models\Publicacao;
use Illuminate\Database\Eloquent\Collection;

// A Interface define o "contrato": lista os métodos que qualquer
// classe que implemente esta interface OBRIGATORIAMENTE deve ter.
// É reutilizável

interface PublicacaoRepositoryInterface
{
    // Cria uma nova publicação na BD a partir do DTO
    public function create(PublicacaoDTO $dto): Publicacao;

    public function getAll(): Collection;

    public function findById(int $id): ?Publicacao;

    public function update(Publicacao $publicacao, UpdatePublicacaoDTO $dto): Publicacao;
    public function delete(Publicacao $publicacao): void;
}
