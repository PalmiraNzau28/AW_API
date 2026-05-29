<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Baze extends Model
{
    use HasFactory;

    protected $table = 'bazes';

    // Esta tabela não tem updated_at
    public $timestamps = false;

    protected $fillable = [
        'publicacao_id',
        'utilizador_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // ─── Relações ─────────────────────────────────────────────────────────
    public function utilizador()
    {
        return $this->belongsTo(Utilizador::class, 'utilizador_id');
    }

    public function publicacao()
    {
        return $this->belongsTo(Publicacao::class, 'publicacao_id');
    }
}
