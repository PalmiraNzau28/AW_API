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
        $table->foreignId('publicacao_id')
              ->constrained('publicacoes')
              ->onDelete('cascade');
        $table->foreignId('utilizador_id')
              ->constrained('utilizadores')
              ->onDelete('cascade');
        $table->timestamp('created_at')->nullable();
        $table->unique(['publicacao_id', 'utilizador_id']);
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
