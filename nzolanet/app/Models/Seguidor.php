<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seguidor extends Model
{
    use HasFactory;

    protected $table = 'seguidores';

    // Esta tabela não tem updated_at
    public $timestamps = false;

    protected $fillable = [
        'seguidor_id',
        'seguido_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // ─── Relações ─────────────────────────────────────────────────────────
    public function seguidor()
    {
        return $this->belongsTo(Utilizador::class, 'seguidor_id');
    }

    public function seguido()
    {
        return $this->belongsTo(Utilizador::class, 'seguido_id');
    }
}
