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
        Schema::create('chatbot_conversations', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->index();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('user_ip');
            $table->text('user_message');
            $table->longText('bot_response');
            $table->string('ai_provider'); // claude, openai, gemini
            $table->string('model_used');
            $table->json('context_data')->nullable(); // relevant system data
            $table->integer('tokens_used')->nullable();
            $table->decimal('cost', 10, 6)->nullable();
            $table->integer('response_time_ms');
            $table->enum('status', ['success', 'error', 'rate_limited'])->default('success');
            $table->text('error_message')->nullable();
            $table->json('metadata')->nullable(); // additional data
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatbot_conversations');
    }
};
