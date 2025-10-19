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
        Schema::create('nim_ranges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_studi_id')->constrained('program_studis')->onDelete('cascade');
            $table->string('tahun_masuk', 4);
            $table->string('prefix', 10);
            $table->integer('current_number')->default(0);
            $table->integer('max_number')->nullable();
            $table->timestamps();

            // Unique constraint: one range per program_studi per year
            $table->unique(['program_studi_id', 'tahun_masuk']);

            // Indexes
            $table->index('program_studi_id');
            $table->index('tahun_masuk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nim_ranges');
    }
};
