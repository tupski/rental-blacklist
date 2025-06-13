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
        Schema::create('topup_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('invoice_number')->unique(); // Nomor invoice unik
            $table->decimal('amount', 15, 2); // Jumlah topup
            $table->enum('payment_method', ['manual', 'midtrans', 'xendit']); // Metode pembayaran
            $table->string('payment_channel')->nullable(); // Channel pembayaran (BCA, GoPay, dll)
            $table->json('payment_details')->nullable(); // Detail pembayaran
            $table->enum('status', ['pending', 'paid', 'confirmed', 'rejected', 'expired'])->default('pending');
            $table->string('proof_of_payment')->nullable(); // Path bukti pembayaran (untuk manual)
            $table->text('notes')->nullable(); // Catatan dari user
            $table->text('admin_notes')->nullable(); // Catatan dari admin
            $table->timestamp('paid_at')->nullable(); // Waktu pembayaran
            $table->timestamp('confirmed_at')->nullable(); // Waktu konfirmasi admin
            $table->timestamp('expires_at')->nullable(); // Waktu kadaluarsa
            $table->json('gateway_response')->nullable(); // Response dari payment gateway
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topup_requests');
    }
};
