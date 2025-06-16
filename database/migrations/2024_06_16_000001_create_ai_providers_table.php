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
        Schema::create('ai_providers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // claude, openai, gemini
            $table->string('display_name');
            $table->text('api_key');
            $table->string('endpoint');
            $table->string('model');
            $table->integer('daily_limit')->default(1000);
            $table->integer('monthly_limit')->default(30000);
            $table->integer('daily_usage')->default(0);
            $table->integer('monthly_usage')->default(0);
            $table->timestamp('last_reset_daily')->nullable();
            $table->timestamp('last_reset_monthly')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('priority')->default(1); // 1 = highest priority
            $table->json('rate_limits')->nullable(); // requests per minute, etc
            $table->json('error_counts')->nullable(); // track errors
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_providers');
    }
};
