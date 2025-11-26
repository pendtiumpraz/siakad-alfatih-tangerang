<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Ubah sistem jadwal dari terikat semester spesifik (2024/2025 Ganjil)
     * menjadi jenis semester generic (Ganjil/Genap)
     * Supaya jadwal bisa dipakai selamanya tanpa input ulang tiap tahun
     */
    public function up(): void
    {
        Schema::table('jadwals', function (Blueprint $table) {
            // Tambah kolom jenis_semester (ganjil/genap)
            $table->enum('jenis_semester', ['ganjil', 'genap'])
                ->default('ganjil')
                ->after('semester_id')
                ->comment('Jenis semester: ganjil atau genap (berlaku untuk semua tahun akademik)');
            
            // Ubah semester_id jadi nullable (untuk transition/backward compatibility)
            $table->foreignId('semester_id')->nullable()->change();
            
            // Tambah index untuk performance
            $table->index(['jenis_semester', 'hari', 'jam_mulai'], 'idx_jadwal_semester_hari_jam');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwals', function (Blueprint $table) {
            // Hapus index
            $table->dropIndex('idx_jadwal_semester_hari_jam');
            
            // Hapus kolom jenis_semester
            $table->dropColumn('jenis_semester');
            
            // Kembalikan semester_id jadi not nullable
            // Note: Ini akan error jika ada data dengan semester_id null
            // $table->foreignId('semester_id')->nullable(false)->change();
        });
    }
};
