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
        // Drop foreign key constraints first
        if (Schema::hasTable('balance_transactions')) {
            Schema::table('balance_transactions', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
            });
        }

        if (Schema::hasTable('topup_requests')) {
            Schema::table('topup_requests', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
            });
        }

        if (Schema::hasTable('user_balances')) {
            Schema::table('user_balances', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
            });
        }

        // Drop tables
        Schema::dropIfExists('balance_transactions');
        Schema::dropIfExists('topup_requests');
        Schema::dropIfExists('user_balances');

        // Remove users with role 'user' (regular users)
        DB::table('users')->where('role', 'user')->delete();

        // Update default role in users table to 'pengusaha_rental'
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('pengusaha_rental')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate user_balances table
        Schema::create('user_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('balance', 15, 2)->default(0);
            $table->timestamps();
            $table->unique('user_id');
        });

        // Recreate balance_transactions table
        Schema::create('balance_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['topup', 'usage', 'refund']);
            $table->decimal('amount', 15, 2);
            $table->decimal('balance_before', 15, 2);
            $table->decimal('balance_after', 15, 2);
            $table->string('description');
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'created_at']);
            $table->index(['reference_type', 'reference_id']);
        });

        // Recreate topup_requests table
        Schema::create('topup_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('invoice_number')->unique();
            $table->decimal('amount', 15, 2);
            $table->enum('payment_method', ['manual', 'midtrans', 'xendit']);
            $table->string('payment_channel')->nullable();
            $table->json('payment_details')->nullable();
            $table->enum('status', ['pending', 'pending_confirmation', 'paid', 'confirmed', 'rejected', 'expired'])->default('pending');
            $table->string('proof_of_payment')->nullable();
            $table->text('notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->json('gateway_response')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'status']);
            $table->index(['status', 'created_at']);
        });

        // Revert users table role default
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('user')->change();
        });
    }
};
