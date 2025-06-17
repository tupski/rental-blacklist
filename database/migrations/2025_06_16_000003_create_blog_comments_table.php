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
        Schema::create('blog_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('blog_posts')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('parent_id')->nullable()->constrained('blog_comments')->onDelete('cascade');
            
            // Guest comment fields
            $table->string('guest_name')->nullable();
            $table->string('guest_email')->nullable();
            $table->string('guest_website')->nullable();
            
            $table->text('content');
            $table->enum('status', ['pending', 'approved', 'rejected', 'spam'])->default('pending');
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            
            // Moderation fields
            $table->foreignId('moderated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('moderated_at')->nullable();
            $table->text('moderation_reason')->nullable();
            
            $table->timestamps();

            $table->index(['post_id', 'status']);
            $table->index(['user_id', 'status']);
            $table->index(['parent_id']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_comments');
    }
};
