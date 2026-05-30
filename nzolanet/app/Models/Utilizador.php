<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;  // cria dados falsos/testes,
use Illuminate\Foundation\Auth\User as Authenticatable;  // login/autenticação
use Illuminate\Notifications\Notifiable;  // notificações
use Tymon\JWTAuth\Contracts\JWTSubject;  // autenticação JWT

class Utilizador extends Authenticatable implements JWTSubject // permite ao model Utilizador suportar autenticação JWT 
{
    use HasFactory, Notifiable; // adicionar funcionalidades das traits

    // Define o nome real da tabela na base de dados
    protected $table = 'utilizadores';

    // Lista de campos que podem ser preenchidos em massa ($fillable)
    protected $fillable = [
        'nome',
        'username',
        'email',
        'password',
        'foto_perfil',
        'bio',
        'perfil_privado',
    ];

    // Campos que NUNCA são expostos nas respostas JSON ($hidden)
    protected $hidden = [
        'password',
    ];

    // Conversão automática de tipos
    protected $casts = [
        'perfil_privado' => 'boolean', // converte automaticamente o 0/1 do MySQL para true/false
        'password'       => 'hashed', // laravel encripta a password automaticamente ao guardar
    ];

    // ─── Métodos obrigatórios do JWT ──────────────────────────────────────
    public function getJWTIdentifier(): mixed // Define quem é o utilizador
    {
        return $this->getKey(); 
    }

    public function getJWTCustomClaims(): array // Adiciona informações extras ao token
    {
        return []; 
    }

    // ─── Relações ─────────────────────────────────────────────────────────
    public function publicacoes()
    {
        return $this->hasMany(Publicacao::class, 'utilizador_id');
    }

    public function comentarios()
    {
        return $this->hasMany(Comentario::class, 'utilizador_id');
    }

    public function bazes()
    {
        return $this->hasMany(Baze::class, 'utilizador_id');
    }

    public function seguidores()
    {
        return $this->belongsToMany(
            Utilizador::class,
            'seguidores', // tabela intermediária guarda os dados da relação entre seguido_id e seguidor_id
            'seguido_id',
            'seguidor_id'
        );
    }

    public function seguindo()
    {
        return $this->belongsToMany(
            Utilizador::class,
            'seguidores',
            'seguidor_id',
            'seguido_id'
        );
    }

    public function notificacoes()
    {
        return $this->hasMany(Notificacao::class, 'utilizador_id');
    }
}
