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
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->boolean('comments_enabled')->default(true)->after('seo_analysis');
            $table->boolean('comments_require_approval')->default(true)->after('comments_enabled');
            $table->integer('comments_count')->default(0)->after('comments_require_approval');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropColumn(['comments_enabled', 'comments_require_approval', 'comments_count']);
        });
    }
};
