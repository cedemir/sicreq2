<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * The column and the index are renamed in separate steps because SQLite
     * rebuilds the index from the columns it reads before the blueprint runs.
     */
    public function up(): void
    {
        Schema::table('historia', function (Blueprint $table) {
            $table->renameColumn('historia_codigo', 'historia_numero');
        });

        Schema::table('historia', function (Blueprint $table) {
            $table->renameIndex(
                'historia_titulo_codigo_exemplar_codigo_historia_codigo_unique',
                'historia_titulo_codigo_exemplar_codigo_historia_numero_unique',
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('historia', function (Blueprint $table) {
            $table->renameColumn('historia_numero', 'historia_codigo');
        });

        Schema::table('historia', function (Blueprint $table) {
            $table->renameIndex(
                'historia_titulo_codigo_exemplar_codigo_historia_numero_unique',
                'historia_titulo_codigo_exemplar_codigo_historia_codigo_unique',
            );
        });
    }
};
