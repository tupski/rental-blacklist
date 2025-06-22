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
        Schema::create('sponsor_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Rental owner yang membeli
            $table->foreignId('sponsor_package_id')->constrained()->onDelete('cascade'); // Paket yang dibeli
            $table->string('invoice_number')->unique(); // Nomor invoice
            $table->decimal('amount', 15, 2); // Jumlah yang harus dibayar
            $table->enum('payment_status', ['pending', 'paid', 'confirmed', 'failed', 'expired'])->default('pending');
            $table->timestamp('payment_deadline'); // Batas waktu pembayaran (24 jam)
            $table->timestamp('paid_at')->nullable(); // Waktu konfirmasi pembayaran
            $table->timestamp('confirmed_at')->nullable(); // Waktu admin konfirmasi
            $table->timestamp('expires_at')->nullable(); // Waktu berakhir sponsor
            $table->string('payment_proof')->nullable(); // File bukti pembayaran
            $table->text('payment_notes')->nullable(); // Catatan pembayaran dari user
            $table->text('admin_notes')->nullable(); // Catatan dari admin
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sponsor_purchases');
    }
};
