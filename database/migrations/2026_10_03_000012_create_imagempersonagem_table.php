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
        Schema::create('imagempersonagem', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('personagem_codigo');
            $table->unsignedInteger('imagem_codigo')->nullable();
            $table->string('imagem_descricao', 150)->nullable();

            $table->foreign('personagem_codigo')->references('personagem_codigo')->on('personagem')->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imagempersonagem');
    }
};
