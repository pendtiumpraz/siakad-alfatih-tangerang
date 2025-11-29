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
        Schema::table('nilais', function (Blueprint $table) {
            // Make dosen_id nullable for batch input by admin
            // Admin might not know which dosen teaches which course
            // Dosen can be assigned later or retrieved from jadwal
            $table->foreignId('dosen_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nilais', function (Blueprint $table) {
            // Revert to NOT NULL (but this might fail if there are null values)
            $table->foreignId('dosen_id')->nullable(false)->change();
        });
    }
};
