<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('type', ['FLASH_SALE', 'PRODUCT_DISCOUNT', 'CATEGORY_DISCOUNT', 'BUNDLE', 'BUY_X_GET_Y']);
            $table->enum('discount_type', ['PERCENTAGE', 'FIXED'])->nullable();
            $table->decimal('discount_value', 10, 2)->nullable();
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->enum('status', ['draft', 'scheduled', 'active', 'expired'])->default('draft');
            $table->unsignedInteger('priority')->default(0);
            $table->timestamps();

            $table->index('slug');
            $table->index('type');
            $table->index('status');
            $table->index('priority');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};