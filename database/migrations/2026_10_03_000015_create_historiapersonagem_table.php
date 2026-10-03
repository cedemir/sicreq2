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
        Schema::create('historiapersonagem', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('titulo_codigo');
            $table->unsignedInteger('exemplar_codigo');
            $table->unsignedInteger('historia_codigo');
            $table->unsignedInteger('personagem_codigo');

            $table->unique(['titulo_codigo', 'exemplar_codigo', 'historia_codigo', 'personagem_codigo'], 'historiapersonagem_chave_unique');

            $table->foreign(['titulo_codigo', 'exemplar_codigo', 'historia_codigo'], 'historiapersonagem_historia_foreign')->references(['titulo_codigo', 'exemplar_codigo', 'historia_codigo'])->on('historia')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('personagem_codigo')->references('personagem_codigo')->on('personagem')->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historiapersonagem');
    }
};
