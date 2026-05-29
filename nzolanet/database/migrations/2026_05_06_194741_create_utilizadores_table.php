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
    Schema::create('utilizadores', function (Blueprint $table) {
        $table->id();
        $table->string('nome', 100);
        $table->string('username', 50)->unique();
        $table->string('email', 150)->unique();
        $table->string('password', 255);
        $table->string('foto_perfil', 255)->nullable(); // este campo pode ser NULL/vazio
        $table->text('bio')->nullable();
        $table->boolean('perfil_privado')->default(0);
        $table->timestamps(); // cria automaticamente duas colunas de data (created_at e updated_up)
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('utilizadores');
    }
};
