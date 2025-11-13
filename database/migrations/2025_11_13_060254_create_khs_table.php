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
        Schema::create('khs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->onDelete('cascade');
            $table->foreignId('semester_id')->constrained('semesters')->onDelete('cascade');
            $table->decimal('ip', 3, 2)->default(0.00)->comment('Indeks Prestasi semester ini');
            $table->decimal('ipk', 3, 2)->default(0.00)->comment('Indeks Prestasi Kumulatif');
            $table->integer('total_sks_semester')->default(0)->comment('Total SKS yang diambil semester ini');
            $table->integer('total_sks_kumulatif')->default(0)->comment('Total SKS kumulatif sampai semester ini');
            $table->timestamps();

            // Unique constraint: one KHS per mahasiswa per semester
            $table->unique(['mahasiswa_id', 'semester_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('khs');
    }
};
