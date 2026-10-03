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
        Schema::create('tamanho', function (Blueprint $table) {
            $table->increments('tamanho_codigo');
            $table->string('tamanho_descricao', 255);
            $table->double('tamanho_linha')->nullable();
            $table->double('tamanho_coluna')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tamanho');
    }
};
