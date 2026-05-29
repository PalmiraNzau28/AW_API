<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('seguidores', function (Blueprint $table) {
        $table->id();
        $table->foreignId('seguidor_id') //cria uma chave estrangeira (seguidor_id) em seguidores ligada à tabela utilizadores (id)
              ->constrained('utilizadores')
              ->onDelete('cascade'); // se utilizadores for apagado apaga automaticamente os registros relacionados a este na tabela seguidores (id)
        $table->foreignId('seguido_id')
              ->constrained('utilizadores')
              ->onDelete('cascade');
        $table->timestamp('created_at')->nullable(); // cria automaticamente uma de duas colunas de data (created_at) que pode ser NULL/vazio
        $table->unique(['seguidor_id', 'seguido_id']); // "estes dois campos juntos não podem repetir a mesma combinação na tabela"
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seguidores');
    }
};
