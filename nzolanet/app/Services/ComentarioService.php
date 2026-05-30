<?php

namespace App\Services;

use App\DTOs\ComentarioDTO;
use App\Repositories\ComentarioRepository;

class ComentarioService
{
    // O Service depende do Repository — injectamos pelo construtor
    public function __construct(
        private ComentarioRepository $repository
    ) {}

    // Lista todos os comentários de uma publicação
    public function listarPorPublicacao(int $publicacao_id): array
    {
        $comentarios = $this->repository->listarPorPublicacao($publicacao_id);

        
        // Converte cada comentário para DTO e depois para array
        return $comentarios->map(function ($comentario) {
            return ComentarioDTO::fromModel($comentario)->toArray();
        })->toArray();
    }

    // Cria um novo comentário
    public function criar(array $dados, int $publicacao_id, int $utilizador_id): array
    {
        // Monta o DTO com os dados recebidos
        $dto = ComentarioDTO::fromRequest($dados, $publicacao_id, $utilizador_id);

        // Guarda na base de dados via Repository
        $comentario = $this->repository->criar([
            'publicacao_id' => $dto->publicacao_id,
            'utilizador_id' => $dto->utilizador_id,
            'texto'         => $dto->texto,
        ]);

        // Recarrega o comentário com os dados do autor
        $comentario->load('utilizador');

        // Retorna o DTO formatado
        return ComentarioDTO::fromModel($comentario)->toArray();
    }

    // Actualiza um comentário — só o autor pode editar
    public function actualizar(int $id, array $dados, int $utilizador_id): array|string
    {
        $comentario = $this->repository->buscarPorId($id);

        // Verifica se o comentário existe
        if (!$comentario) {
            return 'nao_encontrado';
        }

        // Regra de negócio: só o autor pode editar o seu comentário
        if ($comentario->utilizador_id !== $utilizador_id) {
            return 'sem_permissao';
        }

        $actualizado = $this->repository->actualizar($id, [
            'texto' => $dados['texto'],
        ]);

        return ComentarioDTO::fromModel($actualizado)->toArray();
    }

    // Apaga um comentário — só o autor pode apagar
    public function apagar(int $id, int $utilizador_id): string
    {
        $comentario = $this->repository->buscarPorId($id);

        // Verifica se o comentário existe
        if (!$comentario) {
            return 'nao_encontrado';
        }

        // Regra de negócio: só o autor pode apagar o seu comentário
        if ($comentario->utilizador_id !== $utilizador_id) {
            return 'sem_permissao';
        }

        $this->repository->apagar($id);
        return 'apagado';
    }
}