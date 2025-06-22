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
        Schema::create('sponsor_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama paket sponsor
            $table->json('benefits'); // List benefit yang didapat (array)
            $table->decimal('price', 15, 2); // Harga paket
            $table->integer('duration_days'); // Masa berlaku dalam hari
            $table->boolean('is_popular')->default(false); // Badge populer
            $table->json('placement_options'); // Opsi penempatan (home_top, home_bottom, footer, dll)
            $table->integer('max_logo_size_kb')->default(2048); // Maksimal ukuran logo dalam KB
            $table->string('recommended_logo_size')->default('300x150'); // Ukuran logo yang direkomendasikan
            $table->boolean('is_active')->default(true); // Status aktif paket
            $table->integer('sort_order')->default(0); // Urutan tampil
            $table->text('description')->nullable(); // Deskripsi paket
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sponsor_packages');
    }
};
