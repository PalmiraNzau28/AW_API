<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('publicacoes', function (Blueprint $table) {
            $table->index('created_at');
        });

        Schema::table('comentarios', function (Blueprint $table) {
            $table->index(['publicacao_id', 'created_at']);
        });

        Schema::table('utilizadores', function (Blueprint $table) {
            $table->index('nome');
        });
    }

    public function down(): void
    {
        Schema::table('utilizadores', function (Blueprint $table) {
            $table->dropIndex(['nome']);
        });

        Schema::table('comentarios', function (Blueprint $table) {
            $table->dropIndex(['publicacao_id', 'created_at']);
        });

        Schema::table('publicacoes', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
        });
    }
};
