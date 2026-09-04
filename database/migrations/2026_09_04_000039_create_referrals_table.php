<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referrer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('referred_user_id')->constrained()->cascadeOnDelete();
            $table->string('code', 16);
            $table->enum('status', ['pending', 'qualified', 'rewarded'])->default('pending');
            $table->timestamps();

            $table->index(['referrer_id', 'status']);
            $table->index(['referred_user_id', 'code']);
            $table->unique(['referrer_id', 'referred_user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referrals');
    }
};