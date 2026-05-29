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
    Schema::create('bazes', function (Blueprint $table) {
        $table->id();
        $table->foreignId('publicacao_id') //cria uma chave estrangeira (publicacao_id) em bazes ligada à tabela publicacoes (id)
              ->constrained('publicacoes')
              ->onDelete('cascade'); // se publicacoes for apagado apaga automaticamente os registros relacionados a este na tabela bazes (id)
        $table->foreignId('utilizador_id')
              ->constrained('utilizadores')
              ->onDelete('cascade');
        $table->timestamp('created_at')->nullable(); // cria automaticamente uma de duas colunas de data (created_at) que pode ser NULL/vazio
        $table->unique(['publicacao_id', 'utilizador_id']); // "estes dois campos juntos não podem repetir a mesma combinação na tabela"
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bazes');
    }
};
