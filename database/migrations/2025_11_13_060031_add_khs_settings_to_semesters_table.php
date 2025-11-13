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
        Schema::table('semesters', function (Blueprint $table) {
            $table->date('khs_generate_date')->nullable()->after('is_active')
                ->comment('Tanggal otomatis generate KHS untuk semester ini');
            
            $table->boolean('khs_auto_generate')->default(false)->after('khs_generate_date')
                ->comment('Aktifkan auto-generate KHS setiap khs_generate_date');
            
            $table->boolean('khs_show_ketua_prodi_signature')->default(true)->after('khs_auto_generate')
                ->comment('Tampilkan tanda tangan Ketua Prodi di KHS');
            
            $table->boolean('khs_show_dosen_pa_signature')->default(true)->after('khs_show_ketua_prodi_signature')
                ->comment('Tampilkan tanda tangan Dosen PA di KHS');
            
            $table->enum('khs_status', ['draft', 'generated', 'approved', 'published'])
                ->default('draft')
                ->after('khs_show_dosen_pa_signature')
                ->comment('Status KHS: draft=belum generate, generated=sudah generate, approved=sudah disetujui, published=sudah dipublish ke mahasiswa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('semesters', function (Blueprint $table) {
            $table->dropColumn([
                'khs_generate_date',
                'khs_auto_generate',
                'khs_show_ketua_prodi_signature',
                'khs_show_dosen_pa_signature',
                'khs_status',
            ]);
        });
    }
};
