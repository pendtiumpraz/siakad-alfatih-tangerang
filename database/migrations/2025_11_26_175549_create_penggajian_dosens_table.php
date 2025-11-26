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
        Schema::create('penggajian_dosens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dosen_id')->constrained('dosens')->onDelete('cascade');
            $table->string('periode', 20); // Format: 2025-01 (YYYY-MM)
            $table->foreignId('semester_id')->nullable()->constrained('semesters')->onDelete('set null');
            
            // Pengajuan dari dosen
            $table->decimal('total_jam_diajukan', 8, 2); // Total jam mengajar yang diajukan
            $table->text('link_rps')->nullable(); // Link Google Drive RPS
            $table->text('link_materi_ajar')->nullable(); // Link Google Drive Materi Ajar
            $table->text('link_absensi')->nullable(); // Link Google Drive Absensi Mahasiswa
            $table->text('catatan_dosen')->nullable(); // Catatan dari dosen
            
            // Verifikasi dari admin/operator
            $table->enum('status', ['pending', 'verified', 'paid', 'rejected'])->default('pending');
            $table->decimal('total_jam_disetujui', 8, 2)->nullable(); // Jam yang disetujui (bisa beda dengan diajukan)
            $table->text('catatan_verifikasi')->nullable(); // Catatan dari verifikator
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();
            
            // Pembayaran
            $table->decimal('jumlah_dibayar', 15, 2)->nullable(); // Total rupiah yang dibayar
            $table->text('bukti_pembayaran')->nullable(); // Link Google Drive bukti transfer
            $table->foreignId('paid_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('paid_at')->nullable();
            
            $table->timestamps();
            
            // Index untuk query performance
            $table->index(['dosen_id', 'periode']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penggajian_dosens');
    }
};
