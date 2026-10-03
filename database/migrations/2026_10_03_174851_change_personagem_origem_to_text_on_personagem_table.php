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
        Schema::table('personagem', function (Blueprint $table) {
            $table->text('personagem_origem')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personagem', function (Blueprint $table) {
            $table->string('personagem_origem', 255)->nullable()->change();
        });
    }
};
