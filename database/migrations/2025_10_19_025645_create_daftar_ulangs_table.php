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
            $table->foreignId('pendaftar_id')->unique()->constrained('pendaftars')->onDelete('cascade');
            $table->string('nim')->unique();
            $table->decimal('biaya_daftar_ulang', 15, 2);
            $table->enum('pembayaran_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->string('bukti_pembayaran')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();
            $table->json('berkas_uploaded')->nullable();
            $table->enum('status', ['pending', 'completed'])->default('pending');
            $table->timestamp('completed_at')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('pendaftar_id');
            $table->index('nim');
            $table->index('pembayaran_status');
            $table->index('status');
            $table->index('verified_by');
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
