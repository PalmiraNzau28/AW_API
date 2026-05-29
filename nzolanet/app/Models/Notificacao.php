<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notificacao extends Model
{
    use HasFactory;

    protected $table = 'notificacoes';

    // Esta tabela não tem updated_at
    public $timestamps = false;

    protected $fillable = [
        'utilizador_id',
        'tipo',
        'mensagem',
        'lida',
        'referencia_id',
    ];

    protected $casts = [
        'lida'       => 'boolean',
        'created_at' => 'datetime',
    ];

    // ─── Relações ─────────────────────────────────────────────────────────
    public function utilizador()
    {
        return $this->belongsTo(Utilizador::class, 'utilizador_id');
    }
}
