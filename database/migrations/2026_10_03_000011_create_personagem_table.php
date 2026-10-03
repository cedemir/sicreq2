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
        Schema::create('personagem', function (Blueprint $table) {
            $table->increments('personagem_codigo');
            $table->string('personagem_descricao', 50);
            $table->unsignedInteger('universo_codigo');
            $table->string('personagem_origem', 255)->nullable();

            $table->foreign('universo_codigo')->references('universo_codigo')->on('universo')->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personagem');
    }
};
