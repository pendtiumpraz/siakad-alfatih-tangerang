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
        Schema::table('jalur_seleksis', function (Blueprint $table) {
            $table->string('kode_jalur', 20)->unique()->after('id');
            $table->date('tanggal_mulai')->nullable()->after('kuota_total');
            $table->date('tanggal_selesai')->nullable()->after('tanggal_mulai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jalur_seleksis', function (Blueprint $table) {
            $table->dropColumn(['kode_jalur', 'tanggal_mulai', 'tanggal_selesai']);
        });
    }
};
