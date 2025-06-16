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
        // AI Moderation Logs
        Schema::create('ai_moderation_logs', function (Blueprint $table) {
            $table->id();
            $table->string('content_type'); // 'registration', 'report', 'user_profile'
            $table->unsignedBigInteger('content_id'); // ID of the content being moderated
            $table->text('content_text'); // Text content to analyze
            $table->json('analysis_results'); // AI analysis results
            $table->decimal('toxicity_score', 5, 4)->default(0); // 0-1 scale
            $table->decimal('spam_score', 5, 4)->default(0); // 0-1 scale
            $table->decimal('relevance_score', 5, 4)->default(0); // 0-1 scale
            $table->decimal('privacy_risk_score', 5, 4)->default(0); // 0-1 scale
            $table->decimal('overall_risk_score', 5, 4)->default(0); // 0-1 scale
            $table->enum('ai_decision', ['approve', 'flag', 'reject', 'quarantine'])->default('flag');
            $table->text('ai_reasoning')->nullable(); // Why AI made this decision
            $table->enum('admin_decision', ['approve', 'reject', 'pending'])->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamp('processed_at');
            $table->timestamps();

            $table->index(['content_type', 'content_id']);
            $table->index(['ai_decision', 'created_at']);
            $table->index(['overall_risk_score', 'created_at']);
        });

        // AI Risk Profiles
        Schema::create('ai_risk_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->decimal('risk_score', 5, 4)->default(0); // 0-1 scale
            $table->json('risk_factors'); // Detailed risk breakdown
            $table->integer('total_reports')->default(0);
            $table->integer('approved_reports')->default(0);
            $table->integer('rejected_reports')->default(0);
            $table->integer('flagged_reports')->default(0);
            $table->decimal('avg_content_quality', 5, 4)->default(0);
            $table->timestamp('last_calculated_at');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['risk_score', 'updated_at']);
            $table->index(['user_id', 'last_calculated_at']);
        });

        // AI Training Data
        Schema::create('ai_training_data', function (Blueprint $table) {
            $table->id();
            $table->string('content_type');
            $table->text('content_text');
            $table->json('features'); // Extracted features for ML
            $table->enum('human_label', ['spam', 'toxic', 'irrelevant', 'privacy_violation', 'legitimate']);
            $table->decimal('confidence', 5, 4)->default(0); // Human confidence in label
            $table->unsignedBigInteger('labeled_by')->nullable(); // Admin who labeled
            $table->boolean('used_for_training')->default(false);
            $table->timestamps();

            $table->index(['content_type', 'human_label']);
            $table->index(['used_for_training', 'created_at']);
        });

        // AI Model Performance
        Schema::create('ai_model_performance', function (Blueprint $table) {
            $table->id();
            $table->string('model_name');
            $table->string('model_version');
            $table->decimal('accuracy', 5, 4)->default(0);
            $table->decimal('precision', 5, 4)->default(0);
            $table->decimal('recall', 5, 4)->default(0);
            $table->decimal('f1_score', 5, 4)->default(0);
            $table->json('confusion_matrix')->nullable();
            $table->json('feature_importance')->nullable();
            $table->integer('training_samples');
            $table->integer('test_samples');
            $table->timestamp('trained_at');
            $table->boolean('is_active')->default(false);
            $table->timestamps();

            $table->index(['model_name', 'is_active']);
            $table->index(['trained_at', 'accuracy']);
        });

        // Content Flags
        Schema::create('content_flags', function (Blueprint $table) {
            $table->id();
            $table->string('content_type');
            $table->unsignedBigInteger('content_id');
            $table->enum('flag_type', ['spam', 'toxic', 'irrelevant', 'privacy_violation', 'fake', 'duplicate']);
            $table->enum('flag_source', ['ai', 'user_report', 'admin']);
            $table->decimal('confidence', 5, 4)->default(0);
            $table->text('reason')->nullable();
            $table->unsignedBigInteger('flagged_by')->nullable(); // User/Admin ID
            $table->enum('status', ['pending', 'reviewed', 'resolved', 'dismissed'])->default('pending');
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_notes')->nullable();
            $table->timestamps();

            $table->index(['content_type', 'content_id']);
            $table->index(['flag_type', 'status']);
            $table->index(['flag_source', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_flags');
        Schema::dropIfExists('ai_model_performance');
        Schema::dropIfExists('ai_training_data');
        Schema::dropIfExists('ai_risk_profiles');
        Schema::dropIfExists('ai_moderation_logs');
    }
};
