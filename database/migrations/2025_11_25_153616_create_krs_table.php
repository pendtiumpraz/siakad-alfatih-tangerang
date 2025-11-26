<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * KRS (Kartu Rencana Studi) - Sistem pengambilan mata kuliah per semester
     */
    public function up(): void
    {
        Schema::create('krs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->onDelete('cascade');
            $table->foreignId('semester_id')->constrained('semesters')->onDelete('cascade');
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliahs')->onDelete('cascade');
            $table->boolean('is_mengulang')->default(false)->comment('True jika mahasiswa mengulang mata kuliah yang tidak lulus');
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected'])->default('draft')->comment('Status KRS: draft, submitted, approved, rejected');
            $table->text('keterangan')->nullable()->comment('Keterangan tambahan');
            $table->timestamp('submitted_at')->nullable()->comment('Waktu mahasiswa submit KRS');
            $table->timestamp('approved_at')->nullable()->comment('Waktu admin approve KRS');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')->comment('User ID yang approve');
            $table->timestamps();

            // Indexes
            $table->index(['mahasiswa_id', 'semester_id']);
            $table->index('status');
            
            // Unique constraint: mahasiswa tidak bisa ambil mata kuliah yang sama 2x di semester yang sama
            $table->unique(['mahasiswa_id', 'semester_id', 'mata_kuliah_id'], 'krs_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('krs');
    }
};
