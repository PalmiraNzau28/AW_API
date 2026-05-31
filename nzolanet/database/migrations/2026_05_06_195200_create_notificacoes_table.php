<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notificacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('utilizador_id')
                  ->constrained('utilizadores')
                  ->onDelete('cascade');
            $table->enum('tipo', ['baze', 'comentario', 'seguidor']);
            $table->string('mensagem', 255);
            $table->boolean('lida')->default(0);
            $table->unsignedBigInteger('referencia_id')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notificacoes');
    }
};
