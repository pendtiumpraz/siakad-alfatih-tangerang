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
        Schema::create('pendaftars', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pendaftaran')->unique();

            // Basic Information
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('phone', 20);
            $table->string('nik', 16)->unique();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->enum('agama', ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu']);

            // Address Information
            $table->text('alamat');
            $table->string('kelurahan');
            $table->string('kecamatan');
            $table->string('kota_kabupaten');
            $table->string('provinsi');
            $table->string('kode_pos', 10)->nullable();

            // Educational Background
            $table->string('asal_sekolah');
            $table->string('tahun_lulus', 4);
            $table->decimal('nilai_rata_rata', 5, 2)->nullable();

            // Parent Information
            $table->string('nama_ayah');
            $table->string('nama_ibu');
            $table->string('pekerjaan_ayah')->nullable();
            $table->string('pekerjaan_ibu')->nullable();
            $table->string('phone_orangtua', 20)->nullable();

            // Selection Information
            $table->foreignId('jalur_seleksi_id')->constrained('jalur_seleksis')->onDelete('restrict');
            $table->foreignId('program_studi_pilihan_1')->constrained('program_studis')->onDelete('restrict');
            $table->foreignId('program_studi_pilihan_2')->nullable()->constrained('program_studis')->onDelete('restrict');

            // Status
            $table->enum('status', ['pending', 'verified', 'accepted', 'rejected', 'registered'])->default('pending');
            $table->text('keterangan')->nullable();

            // Photo
            $table->string('foto')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('nomor_pendaftaran');
            $table->index('email');
            $table->index('nik');
            $table->index('jalur_seleksi_id');
            $table->index('program_studi_pilihan_1');
            $table->index('status');
            $table->index('tahun_lulus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftars');
    }
};
