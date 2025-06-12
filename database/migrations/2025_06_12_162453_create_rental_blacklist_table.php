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
        Schema::create('rental_blacklist', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 16)->index();
            $table->string('nama_lengkap');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('no_hp', 15);
            $table->text('alamat');
            $table->string('jenis_rental');
            $table->json('jenis_laporan'); // array of laporan types
            $table->enum('status_validitas', ['Pending', 'Valid', 'Invalid'])->default('Pending');
            $table->text('kronologi');
            $table->json('bukti')->nullable(); // array of file paths
            $table->date('tanggal_kejadian');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // Index untuk pencarian
            $table->index(['nik', 'nama_lengkap']);
            $table->index('status_validitas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental_blacklist');
    }
};
