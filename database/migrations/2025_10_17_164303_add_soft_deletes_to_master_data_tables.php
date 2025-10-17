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
        Schema::table('program_studis', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('kurikulums', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('mata_kuliahs', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('ruangans', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('program_studis', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('kurikulums', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('mata_kuliahs', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('ruangans', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
