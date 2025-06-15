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
            $table->boolean('is_banned')->default(false)->after('account_status');
            $table->text('banned_reason')->nullable()->after('is_banned');
            $table->timestamp('banned_at')->nullable()->after('banned_reason');
            $table->unsignedBigInteger('banned_by')->nullable()->after('banned_at');

            $table->foreign('banned_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['banned_by']);
            $table->dropColumn(['is_banned', 'banned_reason', 'banned_at', 'banned_by']);
        });
    }
};
