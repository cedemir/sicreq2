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
        Schema::create('artista_historia', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('titulo_codigo');
            $table->unsignedInteger('exemplar_codigo');
            $table->unsignedInteger('historia_codigo');
            $table->unsignedInteger('artista_codigo');
            $table->smallInteger('participa_artistahistoria')->nullable();

            $table->unique(['titulo_codigo', 'exemplar_codigo', 'historia_codigo', 'artista_codigo'], 'artista_historia_chave_unique');

            $table->foreign(['titulo_codigo', 'exemplar_codigo', 'historia_codigo'], 'artista_historia_historia_foreign')->references(['titulo_codigo', 'exemplar_codigo', 'historia_codigo'])->on('historia')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('artista_codigo')->references('artista_codigo')->on('artista')->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artista_historia');
    }
};
