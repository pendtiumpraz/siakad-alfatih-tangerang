<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ruangans', function (Blueprint $table) {
            // Add tipe column (daring/luring) - default to luring
            $table->enum('tipe', ['daring', 'luring'])->default('luring')->after('jenis');
            
            // Add url column for online/daring rooms
            $table->string('url', 500)->nullable()->after('tipe');
        });

        // Convert existing jenis values: online -> daring, offline -> luring
        DB::table('ruangans')->where('jenis', 'online')->update(['tipe' => 'daring']);
        DB::table('ruangans')->where('jenis', 'offline')->update(['tipe' => 'luring']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ruangans', function (Blueprint $table) {
            $table->dropColumn(['tipe', 'url']);
        });
    }
};
