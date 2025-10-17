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
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->onDelete('cascade');
            $table->foreignId('semester_id')->constrained('semesters')->onDelete('cascade');
            $table->foreignId('operator_id')->nullable()->constrained('operators')->onDelete('set null');
            $table->enum('jenis_pembayaran', ['spp', 'daftar_ulang', 'wisuda', 'lainnya']);
            $table->decimal('jumlah', 15, 2);
            $table->date('tanggal_jatuh_tempo');
            $table->date('tanggal_bayar')->nullable();
            $table->enum('status', ['pending', 'lunas', 'terlambat'])->default('pending');
            $table->string('bukti_pembayaran')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('mahasiswa_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
