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
        Schema::create('user_unlocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('blacklist_id')->constrained('rental_blacklist')->onDelete('cascade');
            $table->decimal('amount_paid', 10, 2);
            $table->timestamp('unlocked_at');
            $table->timestamps();

            // Prevent duplicate unlocks
            $table->unique(['user_id', 'blacklist_id']);

            // Index for faster queries
            $table->index(['user_id', 'unlocked_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_unlocks');
    }
};
