<?php

namespace App\Repositories;

use App\Models\Comentario;

class ComentarioRepository
{
    // Busca todos os comentários de uma publicação
    public function listarPorPublicacao(int $publicacao_id)
    {
        return Comentario::with('utilizador')
            ->where('publicacao_id', $publicacao_id)
            ->orderBy('created_at', 'asc')
            ->get();
    }

    // Busca um comentário pelo ID
    public function buscarPorId(int $id): ?Comentario
    {
        return Comentario::with('utilizador')->find($id);
    }

    // Cria um novo comentário
    public function criar(array $dados): Comentario
    {
        return Comentario::create($dados);
    }

    // Actualiza um comentário existente
    public function actualizar(int $id, array $dados): ?Comentario
    {
        $comentario = Comentario::find($id);

        if (!$comentario) {
            return null;
        }

        $comentario->update($dados);
        return $comentario->fresh('utilizador');
    }

    // Apaga um comentário
    public function apagar(int $id): bool
    {
        $comentario = Comentario::find($id);

        if (!$comentario) {
            return false;
        }

        return $comentario->delete();
    }
}