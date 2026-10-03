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
        Schema::create('titulo', function (Blueprint $table) {
            $table->increments('titulo_codigo');
            $table->string('titulo_descricao', 50);
            $table->unsignedInteger('editora_codigo');
            $table->unsignedInteger('tipotitulo_codigo');
            $table->unsignedInteger('situacao_codigo');
            $table->integer('titulo_periodicidade')->nullable();
            $table->string('titulo_origem', 50)->nullable();

            $table->foreign('editora_codigo')->references('editora_codigo')->on('editora')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('tipotitulo_codigo')->references('tipotitulo_codigo')->on('tipo_titulo')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('situacao_codigo')->references('situacao_codigo')->on('situacao_titulo')->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('titulo');
    }
};
