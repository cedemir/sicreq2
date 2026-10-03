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
        Schema::create('personagem_criador', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('personagem_codigo');
            $table->unsignedInteger('artista_codigo');

            $table->unique(['personagem_codigo', 'artista_codigo']);

            $table->foreign('personagem_codigo')->references('personagem_codigo')->on('personagem')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('artista_codigo')->references('artista_codigo')->on('artista')->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personagem_criador');
    }
};
