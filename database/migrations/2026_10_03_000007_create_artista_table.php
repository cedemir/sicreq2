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
        Schema::create('artista', function (Blueprint $table) {
            $table->increments('artista_codigo');
            $table->string('artista_nome', 50);
            $table->string('artista_historico', 255)->nullable();
            $table->string('artista_foto', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artista');
    }
};
