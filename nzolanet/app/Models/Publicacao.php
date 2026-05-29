<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publicacao extends Model
{
    use HasFactory;

    protected $table = 'publicacoes';

    protected $fillable = [
        'utilizador_id',
        'texto',
        'imagem',
        'video',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ─── Relações ─────────────────────────────────────────────────────────
    public function utilizador()
    {
        return $this->belongsTo(Utilizador::class, 'utilizador_id');
    }

    public function comentarios()
    {
        return $this->hasMany(Comentario::class, 'publicacao_id');
    }

    public function bazes()
    {
        return $this->hasMany(Baze::class, 'publicacao_id');
    }
}