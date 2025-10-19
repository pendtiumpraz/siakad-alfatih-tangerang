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
        Schema::create('pembayaran_pendaftarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftar_id')->constrained('pendaftars')->onDelete('cascade');
            $table->foreignId('jalur_seleksi_id')->constrained('jalur_seleksis')->onDelete('restrict');
            $table->decimal('jumlah', 15, 2);
            $table->string('metode_pembayaran')->nullable();
            $table->string('nomor_referensi')->nullable();
            $table->string('bukti_pembayaran')->nullable();
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('pendaftar_id');
            $table->index('jalur_seleksi_id');
            $table->index('status');
            $table->index('verified_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_pendaftarans');
    }
};
