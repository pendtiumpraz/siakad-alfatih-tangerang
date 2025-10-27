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
        Schema::table('daftar_ulangs', function (Blueprint $table) {
            // Add deleted_at column if it doesn't exist
            if (!Schema::hasColumn('daftar_ulangs', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daftar_ulangs', function (Blueprint $table) {
            // Remove deleted_at column if it exists
            if (Schema::hasColumn('daftar_ulangs', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
};
