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
        Schema::create('spp_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_studi_id')->nullable()->constrained('program_studis')->onDelete('cascade');
            $table->decimal('nominal', 15, 2)->default(250000);
            $table->string('rekening_nama')->default('STAI AL-FATIH TANGERANG');
            $table->string('rekening_nomor')->nullable();
            $table->string('rekening_bank')->nullable();
            $table->string('contact_whatsapp')->nullable();
            $table->string('contact_email')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('jatuh_tempo_hari')->default(14); // 14 hari / 2 minggu
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spp_settings');
    }
};
