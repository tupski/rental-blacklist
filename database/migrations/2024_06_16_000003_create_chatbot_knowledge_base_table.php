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
        Schema::create('chatbot_knowledge_base', function (Blueprint $table) {
            $table->id();
            $table->string('category'); // features, faq, api, admin, etc
            $table->string('title');
            $table->longText('content');
            $table->json('keywords')->nullable(); // for search
            $table->json('related_routes')->nullable(); // related URLs
            $table->json('related_models')->nullable(); // related database models
            $table->integer('priority')->default(1);
            $table->boolean('is_active')->default(true);
            $table->integer('usage_count')->default(0);
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatbot_knowledge_base');
    }
};
