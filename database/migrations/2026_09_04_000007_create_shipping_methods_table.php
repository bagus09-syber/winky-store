<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_methods', function (Blueprint $table) {
            $table->id();
            $table->string('courier');
            $table->string('service');
            $table->string('description')->nullable();
            $table->unsignedInteger('estimated_days')->default(3);
            $table->decimal('base_price', 15, 2)->default(0);
            $table->decimal('price_per_kg', 15, 2)->default(0);
            $table->unsignedInteger('min_weight')->default(0);
            $table->unsignedInteger('max_weight')->default(30000);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['courier', 'service']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_methods');
    }
};
