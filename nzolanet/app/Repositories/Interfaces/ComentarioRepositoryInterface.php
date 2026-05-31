<?php

namespace App\Repositories\Interfaces;

use App\Models\Comentario;

interface ComentarioRepositoryInterface
{
    public function listarPorPublicacao(int $publicacao_id);

    public function buscarPorId(int $id): ?Comentario;

    public function criar(array $dados): Comentario;

    public function actualizar(int $id, array $dados): ?Comentario;

    public function apagar(int $id): bool;
}