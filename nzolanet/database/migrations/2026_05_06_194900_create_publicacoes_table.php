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
    Schema::create('publicacoes', function (Blueprint $table) {
        $table->id();
<<<<<<< HEAD
        $table->foreignId('utilizador_id')
              ->constrained('utilizadores')
              ->onDelete('cascade');
        $table->text('texto')->nullable();
        $table->string('imagem', 255)->nullable();
        $table->string('video', 255)->nullable();
        $table->timestamps();
=======
        $table->foreignId('utilizador_id') //cria uma chave estrangeira (utilizador_id) em publicacoes ligada à tabela utilizadores (id)
              ->constrained('utilizadores') 
              ->onDelete('cascade'); // se utilizadores for apagado apaga automaticamente os registros relacionados a este na tabela publicacoes
        $table->text('texto')->nullable(); // este campo pode ser NULL/vazio
        $table->string('imagem', 255)->nullable();
        $table->string('video', 255)->nullable();
        $table->timestamps(); // cria automaticamente duas colunas de data (created_at e updated_up)
>>>>>>> 2a85dbadae3410ad595aaac69ed606fa6335e014
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publicacoes');
    }
};
