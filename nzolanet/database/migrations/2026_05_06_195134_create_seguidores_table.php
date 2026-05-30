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
        $table->foreignId('seguidor_id')
              ->constrained('utilizadores')
              ->onDelete('cascade');
        $table->foreignId('seguido_id')
              ->constrained('utilizadores')
              ->onDelete('cascade');
        $table->timestamp('created_at')->nullable();
        $table->unique(['seguidor_id', 'seguido_id']);
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
