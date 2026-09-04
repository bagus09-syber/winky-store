<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rewards', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->enum('type', ['POINTS', 'DISCOUNT', 'SHIPPING'])->default('POINTS');
            $table->integer('cost_points');
            $table->enum('discount_type', ['PERCENTAGE', 'FIXED'])->nullable();
            $table->decimal('discount_value', 10, 2)->nullable();
            $table->integer('max_redemptions')->nullable()->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index('slug');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rewards');
    }
};