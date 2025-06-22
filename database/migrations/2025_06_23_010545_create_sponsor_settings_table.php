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
        Schema::create('sponsor_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sponsor_purchase_id')->constrained()->onDelete('cascade'); // Pembelian sponsor
            $table->string('company_name'); // Nama perusahaan rental
            $table->string('logo')->nullable(); // Path logo sponsor
            $table->string('website_url')->nullable(); // URL website
            $table->json('social_media')->nullable(); // Media sosial (facebook, instagram, twitter, dll)
            $table->text('address')->nullable(); // Alamat perusahaan
            $table->string('phone')->nullable(); // Nomor telepon
            $table->string('email')->nullable(); // Email perusahaan
            $table->json('placement_positions'); // Posisi penempatan yang dipilih
            $table->boolean('is_active')->default(true); // Status aktif
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sponsor_settings');
    }
};
