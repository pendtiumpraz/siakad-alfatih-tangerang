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
        // Add foto_url to dosens table
        Schema::table('dosens', function (Blueprint $table) {
            $table->string('foto_url')->nullable()->after('foto');
        });

        // Add foto_url to mahasiswas table
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->string('foto_url')->nullable()->after('foto');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dosens', function (Blueprint $table) {
            $table->dropColumn('foto_url');
        });

        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->dropColumn('foto_url');
        });
    }
};
