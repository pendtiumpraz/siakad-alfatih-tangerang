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
        Schema::table('pembukuan_keuangans', function (Blueprint $table) {
            // Add Google Drive fields after bukti_file
            $table->string('google_drive_file_id')->nullable()->after('bukti_file');
            $table->text('google_drive_link')->nullable()->after('google_drive_file_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembukuan_keuangans', function (Blueprint $table) {
            $table->dropColumn(['google_drive_file_id', 'google_drive_link']);
        });
    }
};
