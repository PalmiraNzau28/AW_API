<?php

namespace App\Repositories;

use App\Models\Seguidor;
use App\Models\Utilizador;
use App\Repositories\Interfaces\SeguidorRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class SeguidorRepository implements SeguidorRepositoryInterface
{
    public function seguir(int $seguidorId, int $seguidoId): void
    {
        Seguidor::create([
            'seguidor_id' => $seguidorId,
            'seguido_id'  => $seguidoId,
        ]);
    }

    public function deixarSeguir(int $seguidorId, int $seguidoId): void
    {
        Seguidor::where('seguidor_id', $seguidorId)
                ->where('seguido_id', $seguidoId)
                ->delete();
    }

    public function jaSeguindo(int $seguidorId, int $seguidoId): bool
    {
        return Seguidor::where('seguidor_id', $seguidorId)
                       ->where('seguido_id', $seguidoId)
                       ->exists();
    }

    public function seguidores(int $utilizadorId): Collection
    {
        $utilizador = Utilizador::find($utilizadorId);

        return $utilizador->seguidores()
                          ->where('seguidor_id', '!=', $utilizadorId)
                          ->get(['utilizadores.id', 'utilizadores.nome', 'utilizadores.username', 'utilizadores.foto_perfil'])
                          ->makeHidden('pivot');
    }

    public function seguindo(int $utilizadorId): Collection
    {
        $utilizador = Utilizador::find($utilizadorId);

        return $utilizador->seguindo()
                          ->where('seguido_id', '!=', $utilizadorId)
                          ->get(['utilizadores.id', 'utilizadores.nome', 'utilizadores.username', 'utilizadores.foto_perfil'])
                          ->makeHidden('pivot');
    }
}