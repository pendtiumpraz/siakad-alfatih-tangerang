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
        Schema::create('kurikulums', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_studi_id')->constrained('program_studis')->onDelete('cascade');
            $table->string('nama_kurikulum');
            $table->year('tahun_mulai');
            $table->year('tahun_selesai')->nullable();
            $table->boolean('is_active')->default(true);
            $table->smallInteger('total_sks');
            $table->timestamps();

            // Indexes
            $table->index('program_studi_id');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kurikulums');
    }
};
