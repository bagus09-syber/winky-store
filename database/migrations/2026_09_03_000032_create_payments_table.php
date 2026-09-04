<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('payment_method'); // qris, bank_transfer, ewallet
            $table->string('transaction_id')->nullable(); // for Midtrans
            $table->decimal('amount', 15, 2);
            $table->string('status')->default('pending'); // pending, paid, failed, expired
            $table->string('payment_type')->nullable(); // for Midtrans response
            $table->string('va_number')->nullable(); // virtual account number
            $table->string('billing_code')->nullable(); // for QRIS
            $table->string('payment_code')->nullable(); // for QRIS
            $table->string('expiry_time')->nullable(); // payment expiry
            $table->json('raw_response')->nullable(); // store full Midtrans response
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
