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
        Schema::create('guest_reports', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 16);
            $table->string('nama_lengkap');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('no_hp', 20);
            $table->text('alamat');
            $table->string('jenis_rental', 100);
            $table->json('jenis_laporan');
            $table->text('kronologi');
            $table->json('bukti')->nullable();
            $table->date('tanggal_kejadian');
            $table->string('email_pelapor');
            $table->string('nama_pelapor');
            $table->string('no_hp_pelapor', 20);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('catatan_admin')->nullable();
            $table->timestamps();

            $table->index(['nik', 'nama_lengkap']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guest_reports');
    }
};
