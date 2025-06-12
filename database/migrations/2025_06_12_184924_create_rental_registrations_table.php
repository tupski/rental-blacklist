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
        Schema::create('rental_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('nama_rental');
            $table->json('jenis_rental');
            $table->text('alamat');
            $table->string('kota', 100);
            $table->string('provinsi', 100);
            $table->string('no_hp', 20);
            $table->string('email');
            $table->string('nama_pemilik');
            $table->string('nik_pemilik', 16);
            $table->string('no_hp_pemilik', 20);
            $table->text('deskripsi')->nullable();
            $table->string('website')->nullable();
            $table->json('sosial_media')->nullable();
            $table->json('dokumen_legalitas')->nullable();
            $table->json('foto_tempat')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('catatan_admin')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();

            $table->index(['nama_rental', 'kota']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental_registrations');
    }
};
