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
    Schema::create('notificacoes', function (Blueprint $table) {
        $table->id();
        $table->foreignId('utilizador_id') //cria uma chave estrangeira (utilizador_id) em notificacoes ligada à tabela utilizadores (id)
              ->constrained('utilizadores')
              ->onDelete('cascade'); // se utilizadores for apagado apaga automaticamente os registros relacionados a este na tabela notificacoes (id)
        $table->enum('tipo', ['baze', 'comentario', 'seguidor']);
        $table->string('mensagem', 255);
        $table->boolean('lida')->default(0);
        $table->unsignedBigInteger('referencia_id')->nullable(); // "cria uma coluna inteira positiva (sem negativos) que pode ficar NULL/vazia"
        $table->timestamp('created_at')->nullable(); // cria automaticamente uma de duas colunas de data (created_at) que pode ser NULL/vazio
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificacoes');
    }
};
