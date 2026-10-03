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
        Schema::create('exemplar', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('titulo_codigo');
            $table->unsignedInteger('exemplar_codigo');
            $table->string('exemplar_subtitulo', 255)->nullable();
            $table->string('exemplar_capa', 100)->nullable();
            $table->timestamp('exemplar_data')->nullable();
            $table->unsignedInteger('armazenagem_codigo')->nullable();
            $table->unsignedInteger('tamanho_codigo')->nullable();
            $table->smallInteger('exemplar_situacao')->nullable();
            $table->integer('exemplar_numeropagina')->nullable();

            $table->unique(['titulo_codigo', 'exemplar_codigo']);

            $table->foreign('titulo_codigo')->references('titulo_codigo')->on('titulo')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('armazenagem_codigo')->references('armazenagem_codigo')->on('armazenagem')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('tamanho_codigo')->references('tamanho_codigo')->on('tamanho')->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exemplar');
    }
};
