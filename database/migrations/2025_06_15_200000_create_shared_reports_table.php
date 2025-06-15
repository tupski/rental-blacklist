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
        Schema::create('shared_reports', function (Blueprint $table) {
            $table->id();
            $table->string('token', 64)->unique();
            $table->foreignId('blacklist_id')->constrained('rental_blacklist')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('password');
            $table->timestamp('expires_at');
            $table->boolean('one_time_view')->default(false);
            $table->boolean('is_accessed')->default(false);
            $table->timestamp('accessed_at')->nullable();
            $table->string('access_ip')->nullable();
            $table->timestamps();

            $table->index(['token', 'expires_at']);
            $table->index(['blacklist_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shared_reports');
    }
};
