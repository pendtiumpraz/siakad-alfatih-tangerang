<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update old grade format to new format
        // AB -> A-, BC -> B-
        DB::table('nilais')
            ->where('grade', 'AB')
            ->update(['grade' => 'A-']);

        DB::table('nilais')
            ->where('grade', 'BC')
            ->update(['grade' => 'B-']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to old format if needed
        DB::table('nilais')
            ->where('grade', 'A-')
            ->update(['grade' => 'AB']);

        DB::table('nilais')
            ->where('grade', 'B-')
            ->update(['grade' => 'BC']);
    }
};
