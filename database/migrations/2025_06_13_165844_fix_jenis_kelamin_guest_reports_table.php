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
        Schema::table('guest_reports', function (Blueprint $table) {
            // Change jenis_kelamin enum to accept L/P
            $table->enum('jenis_kelamin', ['L', 'P', 'Laki-laki', 'Perempuan'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guest_reports', function (Blueprint $table) {
            // Revert back to original enum
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->change();
        });
    }
};
