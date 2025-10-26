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
        // Alter enum to add 'draft' status
        DB::statement("ALTER TABLE pendaftars MODIFY COLUMN status ENUM('draft', 'pending', 'verified', 'accepted', 'rejected', 'registered') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'draft' from enum
        DB::statement("ALTER TABLE pendaftars MODIFY COLUMN status ENUM('pending', 'verified', 'accepted', 'rejected', 'registered') DEFAULT 'pending'");
    }
};
