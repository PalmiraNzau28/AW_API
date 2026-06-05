<?php

namespace App\Repositories\Interfaces;

use App\DTOs\PublicacaoDTO;
use App\DTOs\UpdatePublicacaoDTO;
use App\Models\Publicacao;
use Illuminate\Contracts\Pagination\Paginator;



interface PublicacaoRepositoryInterface
{
    // Cria uma nova publicação na BD a partir do DTO
    public function create(PublicacaoDTO $dto): Publicacao;
    public function getAll(int $perPage = 5): Paginator;
    public function findById(int $id): ?Publicacao;
    public function update(Publicacao $publicacao, UpdatePublicacaoDTO $dto): Publicacao;
    public function delete(Publicacao $publicacao): void;
}
