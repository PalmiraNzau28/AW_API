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
<<<<<<< HEAD
        $table->foreignId('publicacao_id')
              ->constrained('publicacoes')
              ->onDelete('cascade');
=======
        $table->foreignId('publicacao_id') //cria uma chave estrangeira (publicacao_id) em comentarios ligada à tabela publicacoes (id)
              ->constrained('publicacoes')
              ->onDelete('cascade'); // se publicacoes for apagado apaga automaticamente os registros relacionados a este na tabela comentarios
>>>>>>> 2a85dbadae3410ad595aaac69ed606fa6335e014
        $table->foreignId('utilizador_id')
              ->constrained('utilizadores')
              ->onDelete('cascade');
        $table->text('texto');
<<<<<<< HEAD
        $table->timestamps();
=======
        $table->timestamps(); // cria automaticamente duas colunas de data (created_at e updated_up)
>>>>>>> 2a85dbadae3410ad595aaac69ed606fa6335e014
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
