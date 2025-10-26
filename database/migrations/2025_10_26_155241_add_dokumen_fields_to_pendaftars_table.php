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
            // Ijazah/SKL
            $table->string('ijazah')->nullable()->after('foto');
            $table->string('ijazah_google_drive_id')->nullable()->after('ijazah');
            $table->string('ijazah_google_drive_link')->nullable()->after('ijazah_google_drive_id');

            // Transkrip Nilai/Raport
            $table->string('transkrip_nilai')->nullable()->after('ijazah_google_drive_link');
            $table->string('transkrip_google_drive_id')->nullable()->after('transkrip_nilai');
            $table->string('transkrip_google_drive_link')->nullable()->after('transkrip_google_drive_id');

            // KTP
            $table->string('ktp')->nullable()->after('transkrip_google_drive_link');
            $table->string('ktp_google_drive_id')->nullable()->after('ktp');
            $table->string('ktp_google_drive_link')->nullable()->after('ktp_google_drive_id');

            // Kartu Keluarga
            $table->string('kartu_keluarga')->nullable()->after('ktp_google_drive_link');
            $table->string('kk_google_drive_id')->nullable()->after('kartu_keluarga');
            $table->string('kk_google_drive_link')->nullable()->after('kk_google_drive_id');

            // Akta Kelahiran
            $table->string('akta_kelahiran')->nullable()->after('kk_google_drive_link');
            $table->string('akta_google_drive_id')->nullable()->after('akta_kelahiran');
            $table->string('akta_google_drive_link')->nullable()->after('akta_google_drive_id');

            // Surat Keterangan Tidak Mampu (optional, untuk jalur beasiswa)
            $table->string('sktm')->nullable()->after('akta_google_drive_link');
            $table->string('sktm_google_drive_id')->nullable()->after('sktm');
            $table->string('sktm_google_drive_link')->nullable()->after('sktm_google_drive_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftars', function (Blueprint $table) {
            $table->dropColumn([
                'ijazah', 'ijazah_google_drive_id', 'ijazah_google_drive_link',
                'transkrip_nilai', 'transkrip_google_drive_id', 'transkrip_google_drive_link',
                'ktp', 'ktp_google_drive_id', 'ktp_google_drive_link',
                'kartu_keluarga', 'kk_google_drive_id', 'kk_google_drive_link',
                'akta_kelahiran', 'akta_google_drive_id', 'akta_google_drive_link',
                'sktm', 'sktm_google_drive_id', 'sktm_google_drive_link',
            ]);
        });
    }
};
