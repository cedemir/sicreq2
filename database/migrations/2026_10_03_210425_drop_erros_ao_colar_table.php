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
        Schema::dropIfExists('erros_ao_colar');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('erros_ao_colar', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('titulo_codigo')->nullable();
            $table->unsignedInteger('exemplar_codigo')->nullable();
            $table->string('exemplar_subtitulo', 255)->nullable();
            $table->string('exemplar_capa', 255)->nullable();
            $table->timestamp('exemplar_data')->nullable();
            $table->unsignedInteger('armazenagem_codigo')->nullable();
            $table->unsignedInteger('tamanho_codigo')->nullable();
            $table->smallInteger('exemplar_situacao')->nullable();
            $table->integer('exemplar_numeropagina')->nullable();
        });
    }
};
