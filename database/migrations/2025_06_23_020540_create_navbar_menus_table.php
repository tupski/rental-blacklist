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
        Schema::create('navbar_menus', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('url');
            $table->string('icon')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('open_new_tab')->default(false);
            $table->enum('visibility', ['all', 'guest', 'auth', 'admin', 'rental'])->default('all');
            $table->string('route_name')->nullable();
            $table->json('route_params')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('navbar_menus')->onDelete('cascade');
            $table->timestamps();

            $table->index(['is_active', 'order']);
            $table->index('visibility');
            $table->index('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('navbar_menus');
    }
};
