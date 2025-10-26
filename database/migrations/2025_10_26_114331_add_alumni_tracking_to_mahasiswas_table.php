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
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->date('tanggal_lulus')->nullable()->after('status');
            $table->date('tanggal_dropout')->nullable()->after('tanggal_lulus');
            $table->integer('semester_terakhir')->nullable()->after('tanggal_dropout')->comment('Semester terakhir saat lulus/dropout (dibekukan)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->dropColumn(['tanggal_lulus', 'tanggal_dropout', 'semester_terakhir']);
        });
    }
};
