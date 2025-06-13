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
        Schema::create('file_watermarks', function (Blueprint $table) {
            $table->id();
            $table->string('original_path');
            $table->string('watermarked_path')->nullable();
            $table->string('file_type', 10);
            $table->bigInteger('file_size')->nullable();
            $table->string('watermarkable_type');
            $table->unsignedBigInteger('watermarkable_id');
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['watermarkable_type', 'watermarkable_id']);
            $table->index('original_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_watermarks');
    }
};
