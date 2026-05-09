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
        $table->foreignId('publicacao_id')
              ->constrained('publicacoes')
              ->onDelete('cascade');
        $table->foreignId('utilizador_id')
              ->constrained('utilizadores')
              ->onDelete('cascade');
        $table->text('texto');
        $table->timestamps();
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
