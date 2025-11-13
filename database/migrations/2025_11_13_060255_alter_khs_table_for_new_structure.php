<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Mengubah struktur table khs dari struktur lama ke struktur baru:
     * Lama: total_sks, total_sks_lulus, ip_semester, ip_kumulatif, status_semester
     * Baru: ip, ipk, total_sks_semester, total_sks_kumulatif
     */
    public function up(): void
    {
        Schema::table('khs', function (Blueprint $table) {
            // Check if old columns exist before modifying
            $hasOldColumns = Schema::hasColumn('khs', 'ip_semester');

            if ($hasOldColumns) {
                // Rename kolom lama ke baru
                $table->renameColumn('ip_semester', 'ip');
                $table->renameColumn('ip_kumulatif', 'ipk');
                $table->renameColumn('total_sks', 'total_sks_semester');
                
                // Add new column
                $table->integer('total_sks_kumulatif')->default(0)->comment('Total SKS kumulatif sampai semester ini')->after('total_sks_semester');
                
                // Drop kolom yang tidak dipakai
                $table->dropColumn(['total_sks_lulus', 'status_semester']);
            } else {
                // Struktur sudah baru, cek apakah kolom ip ada
                if (!Schema::hasColumn('khs', 'ip')) {
                    $table->decimal('ip', 3, 2)->default(0.00)->comment('Indeks Prestasi semester ini')->after('semester_id');
                }
                
                if (!Schema::hasColumn('khs', 'ipk')) {
                    $table->decimal('ipk', 3, 2)->default(0.00)->comment('Indeks Prestasi Kumulatif')->after('ip');
                }
                
                if (!Schema::hasColumn('khs', 'total_sks_semester')) {
                    $table->integer('total_sks_semester')->default(0)->comment('Total SKS yang diambil semester ini')->after('ipk');
                }
                
                if (!Schema::hasColumn('khs', 'total_sks_kumulatif')) {
                    $table->integer('total_sks_kumulatif')->default(0)->comment('Total SKS kumulatif sampai semester ini')->after('total_sks_semester');
                }
            }
        });

        // Update comment pada kolom ip dan ipk untuk konsistensi
        DB::statement("ALTER TABLE khs MODIFY COLUMN ip DECIMAL(3,2) NOT NULL DEFAULT 0.00 COMMENT 'Indeks Prestasi semester ini'");
        DB::statement("ALTER TABLE khs MODIFY COLUMN ipk DECIMAL(3,2) NOT NULL DEFAULT 0.00 COMMENT 'Indeks Prestasi Kumulatif'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('khs', function (Blueprint $table) {
            // Rollback: kembalikan ke struktur lama
            if (Schema::hasColumn('khs', 'ip')) {
                $table->renameColumn('ip', 'ip_semester');
                $table->renameColumn('ipk', 'ip_kumulatif');
                $table->renameColumn('total_sks_semester', 'total_sks');
                
                // Add back old columns
                $table->smallInteger('total_sks_lulus')->default(0)->after('total_sks');
                $table->enum('status_semester', ['naik', 'mengulang', 'cuti'])->default('naik')->after('ip_kumulatif');
                
                // Drop new column
                $table->dropColumn('total_sks_kumulatif');
            }
        });
    }
};
