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
        Schema::create('historia', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('titulo_codigo');
            $table->unsignedInteger('exemplar_codigo');
            $table->unsignedInteger('historia_codigo');
            $table->string('historia_titulo', 50);
            $table->string('historia_resumo', 255)->nullable();
            $table->smallInteger('historia_cor')->default(0);
            $table->string('historia_original', 50)->nullable();

            $table->unique(['titulo_codigo', 'exemplar_codigo', 'historia_codigo']);

            $table->foreign(['titulo_codigo', 'exemplar_codigo'])->references(['titulo_codigo', 'exemplar_codigo'])->on('exemplar')->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historia');
    }
};
