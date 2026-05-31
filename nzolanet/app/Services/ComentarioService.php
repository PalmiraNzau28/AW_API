<?php

namespace App\Services;

use App\DTOs\ComentarioDTO;
use App\DTOs\UpdateComentarioDTO;
use App\Repositories\Interfaces\ComentarioRepositoryInterface;
use App\Repositories\NotificacaoRepository;

class ComentarioService
{
    public function __construct(
        private ComentarioRepositoryInterface $repository,
        private NotificacaoRepository $notificacaoRepository,
    ) {}

    public function listarPorPublicacao(int $publicacao_id): array
    {
        $comentarios = $this->repository->listarPorPublicacao($publicacao_id);

        return $comentarios->map(function ($comentario) {
            return ComentarioDTO::fromModel($comentario)->toArray();
        })->toArray();
    }

    public function criar(array $dados, int $publicacao_id, int $utilizador_id): array
    {
        $dto = ComentarioDTO::fromRequest($dados, $publicacao_id, $utilizador_id);

        $comentario = $this->repository->criar([
            'publicacao_id' => $dto->publicacao_id,
            'utilizador_id' => $dto->utilizador_id,
            'texto'         => $dto->texto,
        ]);

        $comentario->load('utilizador');

        // Gerar notificacao para o autor da publicacao
        $publicacao = $this->repository->buscarPublicacao($publicacao_id);

        if ($publicacao && $publicacao->utilizador_id !== $utilizador_id) {
            $this->notificacaoRepository->criar([
                'utilizador_id' => $publicacao->utilizador_id,
                'tipo'          => 'comentario',
                'mensagem'      => $comentario->utilizador->nome . ' comentou na tua publicação.',
                'referencia_id' => $comentario->id,
            ]);
        }

        return ComentarioDTO::fromModel($comentario)->toArray();
    }

    public function actualizar(int $id, array $dados, int $utilizador_id): array|string
    {
        $comentario = $this->repository->buscarPorId($id);

        if (!$comentario) {
            return 'nao_encontrado';
        }

        if ($comentario->utilizador_id !== $utilizador_id) {
            return 'sem_permissao';
        }

        $dto = UpdateComentarioDTO::fromRequest($dados);

        $actualizado = $this->repository->actualizar($id, [
            'texto' => $dto->texto,
        ]);

        return ComentarioDTO::fromModel($actualizado)->toArray();
    }

    public function apagar(int $id, int $utilizador_id): string
    {
        $comentario = $this->repository->buscarPorId($id);

        if (!$comentario) {
            return 'nao_encontrado';
        }

        if ($comentario->utilizador_id !== $utilizador_id) {
            return 'sem_permissao';
        }

        $this->repository->apagar($id);
        return 'apagado';
    }
}