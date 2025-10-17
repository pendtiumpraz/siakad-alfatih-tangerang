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
        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('nim')->unique();
            $table->string('nama_lengkap');
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->text('alamat')->nullable();
            $table->string('no_telepon')->nullable();
            $table->string('foto')->nullable();
            $table->foreignId('program_studi_id')->constrained('program_studis')->onDelete('cascade');
            $table->year('angkatan');
            $table->tinyInteger('semester_aktif')->default(1);
            $table->enum('status', ['aktif', 'cuti', 'lulus', 'dropout'])->default('aktif');
            $table->timestamps();

            // Indexes
            $table->index('nim');
            $table->index('program_studi_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswas');
    }
};
