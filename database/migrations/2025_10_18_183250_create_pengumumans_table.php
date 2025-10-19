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
        Schema::create('pengumumans', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('isi');
            $table->enum('tipe', ['info', 'penting', 'pengingat', 'kegiatan'])->default('info');
            $table->foreignId('pembuat_id')->constrained('users')->onDelete('cascade');
            $table->enum('pembuat_role', ['super_admin', 'dosen', 'operator']);
            $table->boolean('untuk_mahasiswa')->default(true);
            $table->boolean('is_active')->default(true);
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('pembuat_id');
            $table->index('is_active');
            $table->index('tanggal_mulai');
            $table->index('tanggal_selesai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengumumans');
    }
};
