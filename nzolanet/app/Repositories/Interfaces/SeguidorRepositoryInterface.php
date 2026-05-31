<?php

namespace App\Repositories\Interfaces;

use App\Models\Utilizador;
use Illuminate\Database\Eloquent\Collection;

interface SeguidorRepositoryInterface
{
    public function seguir(int $seguidorId, int $seguidoId): void;
    public function deixarSeguir(int $seguidorId, int $seguidoId): void;
    public function jaSeguindo(int $seguidorId, int $seguidoId): bool;
    public function seguidores(int $utilizadorId): Collection;
    public function seguindo(int $utilizadorId): Collection;
}