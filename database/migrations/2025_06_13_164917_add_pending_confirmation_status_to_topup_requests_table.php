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
        Schema::table('topup_requests', function (Blueprint $table) {
            // Modify the enum to include 'pending_confirmation'
            $table->enum('status', ['pending', 'pending_confirmation', 'paid', 'confirmed', 'rejected', 'expired'])
                  ->default('pending')
                  ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('topup_requests', function (Blueprint $table) {
            // Revert back to original enum
            $table->enum('status', ['pending', 'paid', 'confirmed', 'rejected', 'expired'])
                  ->default('pending')
                  ->change();
        });
    }
};
