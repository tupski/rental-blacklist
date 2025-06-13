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
        Schema::create('balance_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['topup', 'usage', 'refund']); // topup, usage (lihat detail), refund
            $table->decimal('amount', 15, 2); // Jumlah transaksi
            $table->decimal('balance_before', 15, 2); // Saldo sebelum transaksi
            $table->decimal('balance_after', 15, 2); // Saldo setelah transaksi
            $table->string('description'); // Deskripsi transaksi
            $table->string('reference_type')->nullable(); // Model yang direferensikan (RentalBlacklist, TopupRequest)
            $table->unsignedBigInteger('reference_id')->nullable(); // ID dari model yang direferensikan
            $table->json('metadata')->nullable(); // Data tambahan
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index(['reference_type', 'reference_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('balance_transactions');
    }
};
