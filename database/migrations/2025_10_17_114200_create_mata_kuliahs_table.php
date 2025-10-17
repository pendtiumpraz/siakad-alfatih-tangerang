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
        Schema::create('mata_kuliahs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kurikulum_id')->constrained('kurikulums')->onDelete('cascade');
            $table->string('kode_mk')->unique();
            $table->string('nama_mk');
            $table->tinyInteger('sks');
            $table->tinyInteger('semester');
            $table->enum('jenis', ['wajib', 'pilihan'])->default('wajib');
            $table->text('deskripsi')->nullable();
            $table->timestamps();

            // Index
            $table->index('kode_mk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mata_kuliahs');
    }
};
