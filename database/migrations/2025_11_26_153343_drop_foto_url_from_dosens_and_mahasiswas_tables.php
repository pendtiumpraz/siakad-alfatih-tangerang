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
        // Drop foto_url from dosens table (redundant, only foto needed for Google Drive file ID)
        Schema::table('dosens', function (Blueprint $table) {
            $table->dropColumn('foto_url');
        });

        // Drop foto_url from mahasiswas table
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->dropColumn('foto_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore foto_url to dosens table
        Schema::table('dosens', function (Blueprint $table) {
            $table->string('foto_url')->nullable()->after('foto');
        });

        // Restore foto_url to mahasiswas table
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->string('foto_url')->nullable()->after('foto');
        });
    }
};
