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
        Schema::table('rental_blacklist', function (Blueprint $table) {
            // Informasi Pelapor (untuk guest reports)
            $table->string('nama_perusahaan_rental')->nullable()->after('user_id');
            $table->string('nama_penanggung_jawab')->nullable()->after('nama_perusahaan_rental');
            $table->string('no_wa_pelapor')->nullable()->after('nama_penanggung_jawab');
            $table->string('email_pelapor')->nullable()->after('no_wa_pelapor');
            $table->text('alamat_usaha')->nullable()->after('email_pelapor');
            $table->string('website_usaha')->nullable()->after('alamat_usaha');
            
            // Data Penyewa - update existing fields
            $table->string('nik', 16)->nullable()->change(); // Make NIK optional
            $table->json('foto_penyewa')->nullable()->after('alamat'); // Array of photo paths
            $table->json('foto_ktp_sim')->nullable()->after('foto_penyewa'); // Array of ID photo paths
            
            // Detail Masalah - new fields
            $table->date('tanggal_sewa')->nullable()->after('tanggal_kejadian');
            $table->string('jenis_kendaraan')->nullable()->after('jenis_rental');
            $table->string('nomor_polisi')->nullable()->after('jenis_kendaraan');
            $table->decimal('nilai_kerugian', 15, 2)->nullable()->after('nomor_polisi');
            
            // Status Penanganan
            $table->json('status_penanganan')->nullable()->after('kronologi'); // Array of status
            $table->string('status_lainnya')->nullable()->after('status_penanganan');
            
            // Persetujuan
            $table->boolean('persetujuan')->default(false)->after('status_lainnya');
            $table->string('nama_pelapor_ttd')->nullable()->after('persetujuan');
            $table->timestamp('tanggal_pelaporan')->nullable()->after('nama_pelapor_ttd');
            
            // Type pelapor (rental/guest)
            $table->enum('tipe_pelapor', ['rental', 'guest'])->default('rental')->after('tanggal_pelaporan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rental_blacklist', function (Blueprint $table) {
            $table->dropColumn([
                'nama_perusahaan_rental',
                'nama_penanggung_jawab', 
                'no_wa_pelapor',
                'email_pelapor',
                'alamat_usaha',
                'website_usaha',
                'foto_penyewa',
                'foto_ktp_sim',
                'tanggal_sewa',
                'jenis_kendaraan',
                'nomor_polisi',
                'nilai_kerugian',
                'status_penanganan',
                'status_lainnya',
                'persetujuan',
                'nama_pelapor_ttd',
                'tanggal_pelaporan',
                'tipe_pelapor'
            ]);
            
            // Revert NIK to required
            $table->string('nik', 16)->nullable(false)->change();
        });
    }
};
