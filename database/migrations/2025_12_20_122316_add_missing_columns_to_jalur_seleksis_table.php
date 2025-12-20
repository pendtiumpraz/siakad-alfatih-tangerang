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
        // Step 1: Add columns without unique constraint first
        Schema::table('jalur_seleksis', function (Blueprint $table) {
            $table->string('kode_jalur', 20)->nullable()->after('id');
            $table->date('tanggal_mulai')->nullable()->after('kuota_total');
            $table->date('tanggal_selesai')->nullable()->after('tanggal_mulai');
        });

        // Step 2: Populate existing rows with unique kode_jalur based on ID
        $jalurSeleksis = DB::table('jalur_seleksis')->get();
        foreach ($jalurSeleksis as $jalur) {
            DB::table('jalur_seleksis')
                ->where('id', $jalur->id)
                ->update(['kode_jalur' => 'JALUR-' . str_pad($jalur->id, 3, '0', STR_PAD_LEFT)]);
        }

        // Step 3: Now add the unique constraint using raw SQL
        DB::statement('ALTER TABLE jalur_seleksis MODIFY kode_jalur VARCHAR(20) NOT NULL');
        DB::statement('ALTER TABLE jalur_seleksis ADD UNIQUE jalur_seleksis_kode_jalur_unique(kode_jalur)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jalur_seleksis', function (Blueprint $table) {
            $table->dropColumn(['kode_jalur', 'tanggal_mulai', 'tanggal_selesai']);
        });
    }
};
