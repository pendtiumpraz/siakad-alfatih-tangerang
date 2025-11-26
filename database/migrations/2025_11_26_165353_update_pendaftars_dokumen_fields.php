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
        Schema::table('pendaftars', function (Blueprint $table) {
            // HANYA MENAMBAHKAN kolom baru - TIDAK MENGHAPUS kolom lama untuk keamanan data production
            
            // Surat Bukti Mengajar (khusus guru)
            $table->string('surat_mengajar')->nullable()->after('sktm_google_drive_link');
            $table->string('surat_mengajar_google_drive_id')->nullable()->after('surat_mengajar');
            $table->string('surat_mengajar_google_drive_link')->nullable()->after('surat_mengajar_google_drive_id');
            
            // Surat Keterangan RT Dhuafa (khusus dhuafa)
            $table->string('surat_rt_dhuafa')->nullable()->after('surat_mengajar_google_drive_link');
            $table->string('surat_rt_dhuafa_google_drive_id')->nullable()->after('surat_rt_dhuafa');
            $table->string('surat_rt_dhuafa_google_drive_link')->nullable()->after('surat_rt_dhuafa_google_drive_id');
            
            // Surat Keterangan Yatim dari RT (khusus yatim)
            $table->string('surat_rt_yatim')->nullable()->after('surat_rt_dhuafa_google_drive_link');
            $table->string('surat_rt_yatim_google_drive_id')->nullable()->after('surat_rt_yatim');
            $table->string('surat_rt_yatim_google_drive_link')->nullable()->after('surat_rt_yatim_google_drive_id');
            
            // Sertifikat Quran (khusus penghafal quran - minimal juz 30)
            $table->string('sertifikat_quran')->nullable()->after('surat_rt_yatim_google_drive_link');
            $table->string('sertifikat_quran_google_drive_id')->nullable()->after('sertifikat_quran');
            $table->string('sertifikat_quran_google_drive_link')->nullable()->after('sertifikat_quran_google_drive_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftars', function (Blueprint $table) {
            // Drop kolom baru saja yang ditambahkan
            $table->dropColumn([
                'surat_mengajar',
                'surat_mengajar_google_drive_id',
                'surat_mengajar_google_drive_link',
                'surat_rt_dhuafa',
                'surat_rt_dhuafa_google_drive_id',
                'surat_rt_dhuafa_google_drive_link',
                'surat_rt_yatim',
                'surat_rt_yatim_google_drive_id',
                'surat_rt_yatim_google_drive_link',
                'sertifikat_quran',
                'sertifikat_quran_google_drive_id',
                'sertifikat_quran_google_drive_link',
            ]);
        });
    }
};
