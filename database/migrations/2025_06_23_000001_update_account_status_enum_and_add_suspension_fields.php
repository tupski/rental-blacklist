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
        Schema::table('users', function (Blueprint $table) {
            // Update enum to include needs_revision
            $table->enum('account_status', ['active', 'pending', 'needs_revision', 'suspended'])->default('active')->change();
            
            // Add fields for suspension management
            $table->text('suspension_reason')->nullable()->after('banned_by');
            $table->enum('suspension_type', ['permanent', 'temporary'])->nullable()->after('suspension_reason');
            $table->integer('suspension_days')->nullable()->after('suspension_type');
            $table->timestamp('suspended_at')->nullable()->after('suspension_days');
            $table->timestamp('suspension_ends_at')->nullable()->after('suspended_at');
            $table->unsignedBigInteger('suspended_by')->nullable()->after('suspension_ends_at');
            
            // Add field for revision notes
            $table->text('revision_notes')->nullable()->after('suspended_by');
            $table->timestamp('revision_requested_at')->nullable()->after('revision_notes');
            $table->unsignedBigInteger('revision_requested_by')->nullable()->after('revision_requested_at');

            // Add foreign keys
            $table->foreign('suspended_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('revision_requested_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['suspended_by']);
            $table->dropForeign(['revision_requested_by']);
            
            // Drop columns
            $table->dropColumn([
                'suspension_reason',
                'suspension_type', 
                'suspension_days',
                'suspended_at',
                'suspension_ends_at',
                'suspended_by',
                'revision_notes',
                'revision_requested_at',
                'revision_requested_by'
            ]);
            
            // Revert enum to original values
            $table->enum('account_status', ['active', 'pending', 'suspended'])->default('active')->change();
        });
    }
};
