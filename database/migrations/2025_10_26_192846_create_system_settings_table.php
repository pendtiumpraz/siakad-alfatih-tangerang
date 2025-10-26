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
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Setting key (e.g., 'spmb_email', 'bank_name')
            $table->text('value')->nullable(); // Setting value
            $table->string('group')->default('general'); // Group settings (spmb, payment, pricing, etc)
            $table->string('type')->default('text'); // Data type (text, number, boolean, json)
            $table->text('description')->nullable(); // Description of what this setting does
            $table->timestamps();

            // Indexes for faster queries
            $table->index('key');
            $table->index('group');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
