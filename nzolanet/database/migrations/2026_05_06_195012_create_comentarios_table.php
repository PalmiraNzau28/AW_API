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
    Schema::create('comentarios', function (Blueprint $table) {
        $table->id();
        $table->foreignId('publicacao_id') //cria uma chave estrangeira (publicacao_id) em comentarios ligada à tabela publicacoes (id)
              ->constrained('publicacoes')
              ->onDelete('cascade'); // se publicacoes for apagado apaga automaticamente os registros relacionados a este na tabela comentarios
        $table->foreignId('utilizador_id')
              ->constrained('utilizadores')
              ->onDelete('cascade');
        $table->text('texto');
        $table->timestamps(); // cria automaticamente duas colunas de data (created_at e updated_up)
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comentarios');
    }
};
