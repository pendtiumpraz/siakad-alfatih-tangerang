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
        Schema::create('pembukuan_keuangans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('semester_id')->nullable()->constrained()->onDelete('set null');
            
            // Jenis transaksi
            $table->enum('jenis', ['pemasukan', 'pengeluaran']);
            
            // Kategori utama
            $table->enum('kategori', [
                'spmb_daftar_ulang',  // Auto from SPMB + Daftar Ulang
                'spp',                 // Auto from SPP
                'gaji_dosen',          // Auto from Penggajian
                'lain_lain'            // Manual input
            ]);
            
            // Sub-kategori untuk lain-lain
            $table->string('sub_kategori')->nullable();
            
            // Financial data
            $table->decimal('nominal', 15, 2);
            $table->text('keterangan')->nullable();
            $table->date('tanggal');
            
            // Auto vs Manual
            $table->boolean('is_otomatis')->default(false);
            
            // Reference to original transaction (polymorphic)
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('reference_type')->nullable();
            
            // Bukti/Attachment (optional)
            $table->string('bukti_file')->nullable();
            
            // Tracking
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for better query performance
            $table->index(['semester_id', 'jenis', 'kategori']);
            $table->index(['tanggal']);
            $table->index(['is_otomatis']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembukuan_keuangans');
    }
};
