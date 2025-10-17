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
            $table->smallInteger('total_sks')->default(0);
            $table->smallInteger('total_sks_lulus')->default(0);
            $table->decimal('ip_semester', 3, 2)->nullable();
            $table->decimal('ip_kumulatif', 3, 2)->nullable();
            $table->enum('status_semester', ['naik', 'mengulang', 'cuti'])->default('naik');
            $table->timestamps();

            // Unique key
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
