<?php

namespace App\Services;

use App\Repositories\Interfaces\SeguidorRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class SeguidorService
{
    public function __construct(
        private readonly SeguidorRepositoryInterface $seguidorRepository,
    ) {}

    public function seguir(int $seguidoId): array
    {
        $seguidorId = Auth::id();

        // Não pode seguir-se a si próprio
        if ($seguidorId === $seguidoId) {
            return [
                'sucesso'   => false,
                'mensagem'  => 'Não podes seguir-te a ti próprio.',
            ];
        }

        // Verifica se já está a seguir
        if ($this->seguidorRepository->jaSeguindo($seguidorId, $seguidoId)) {
            return [
                'sucesso'   => false,
                'mensagem'  => 'Já estás a seguir este utilizador.',
            ];
        }

        $this->seguidorRepository->seguir($seguidorId, $seguidoId);

        return [
            'sucesso'  => true,
            'mensagem' => 'Utilizador seguido com sucesso.',
        ];
    }

    public function deixarSeguir(int $seguidoId): array
    {
        $seguidorId = Auth::id();

        // Verifica se está realmente a seguir
        if (!$this->seguidorRepository->jaSeguindo($seguidorId, $seguidoId)) {
            return [
                'sucesso'  => false,
                'mensagem' => 'Não estás a seguir este utilizador.',
            ];
        }

        $this->seguidorRepository->deixarSeguir($seguidorId, $seguidoId);

        return [
            'sucesso'  => true,
            'mensagem' => 'Deixaste de seguir o utilizador com sucesso.',
        ];
    }

    public function seguidores(int $utilizadorId): Collection
    {
        return $this->seguidorRepository->seguidores($utilizadorId);
    }

    public function seguindo(int $utilizadorId): Collection
    {
        return $this->seguidorRepository->seguindo($utilizadorId);
    }
}