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
        Schema::create('daftar_ulangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftar_id')->constrained('pendaftars')->onDelete('cascade');

            // Status: pending, verified, rejected
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');

            // NIM Sementara (gunakan nomor pendaftaran)
            $table->string('nim_sementara')->unique();

            // Biaya Daftar Ulang
            $table->decimal('biaya_daftar_ulang', 15, 2)->default(0);
            $table->string('metode_pembayaran')->nullable(); // transfer, va, tunai
            $table->string('nomor_referensi')->nullable();
            $table->text('bukti_pembayaran')->nullable(); // Google Drive URL

            // Dokumen Tambahan (JSON array of Google Drive URLs)
            $table->json('dokumen_tambahan')->nullable();

            // Verification info
            $table->text('keterangan')->nullable();
            $table->timestamp('tanggal_verifikasi')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');

            // Mahasiswa user yang di-create (jika sudah verified)
            $table->foreignId('mahasiswa_user_id')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('status');
            $table->index('nim_sementara');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daftar_ulangs');
    }
};
