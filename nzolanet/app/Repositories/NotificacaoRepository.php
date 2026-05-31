<?php

namespace App\Repositories;

use App\Models\Notificacao;

class NotificacaoRepository
{
    public function criar(array $dados): Notificacao
    {
        return Notificacao::create($dados);
    }
}