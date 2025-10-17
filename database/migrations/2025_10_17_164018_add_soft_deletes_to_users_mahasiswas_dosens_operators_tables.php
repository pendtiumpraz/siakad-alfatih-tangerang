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
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('dosens', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('operators', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('dosens', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('operators', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
