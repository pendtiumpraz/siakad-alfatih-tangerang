<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Convert old grade format (AB, BC) to standard format (A-, B-)
     */
    public function up(): void
    {
        // Convert AB to A- in nilais table
        DB::table('nilais')
            ->where('grade', 'AB')
            ->update(['grade' => 'A-']);

        // Convert BC to B- in nilais table
        DB::table('nilais')
            ->where('grade', 'BC')
            ->update(['grade' => 'B-']);
    }

    /**
     * Reverse the migrations.
     * Convert back to old format if needed
     */
    public function down(): void
    {
        // Convert A- back to AB
        DB::table('nilais')
            ->where('grade', 'A-')
            ->update(['grade' => 'AB']);

        // Convert B- back to BC
        DB::table('nilais')
            ->where('grade', 'B-')
            ->update(['grade' => 'BC']);
    }
};
