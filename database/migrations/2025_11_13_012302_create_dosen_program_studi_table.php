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
        Schema::create('dosen_program_studi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dosen_id')->constrained('dosens')->onDelete('cascade');
            $table->foreignId('program_studi_id')->constrained('program_studis')->onDelete('cascade');
            $table->timestamps();

            // Prevent duplicate assignments
            $table->unique(['dosen_id', 'program_studi_id']);
            
            // Indexes for performance
            $table->index('dosen_id');
            $table->index('program_studi_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosen_program_studi');
    }
};
