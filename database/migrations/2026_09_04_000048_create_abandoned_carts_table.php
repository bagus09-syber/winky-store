<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('abandoned_carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('cart_id', 32)->nullable();
            $table->enum('status', ['active', 'abandoned', 'recovered'])->default('active');
            $table->timestamp('last_activity_at')->useCurrent();
            $table->timestamp('recovered_at')->nullable();

            $table->index('status');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('abandoned_carts');
    }
};