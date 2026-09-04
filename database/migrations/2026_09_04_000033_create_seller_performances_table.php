<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seller_performances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('total_orders')->default(0);
            $table->unsignedInteger('completed_orders')->default(0);
            $table->unsignedInteger('cancelled_orders')->default(0);
            $table->decimal('response_rate', 5, 2)->default(0);
            $table->decimal('on_time_shipping_rate', 5, 2)->default(0);
            $table->decimal('average_rating', 3, 2)->default(0);
            $table->decimal('return_rate', 5, 2)->default(0);
            $table->decimal('seller_score', 5, 2)->default(0);
            $table->enum('seller_level', ['bronze', 'silver', 'gold', 'platinum', 'diamond'])->default('bronze');
            $table->timestamp('last_calculated_at')->nullable();
            $table->timestamps();

            $table->unique('store_id');
            $table->index('seller_level');
            $table->index('seller_score');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('is_read_by_seller')->default(false)->after('seller_status');
            $table->boolean('is_read_by_buyer')->default(false)->after('is_read_by_seller');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['is_read_by_seller', 'is_read_by_buyer']);
        });

        Schema::dropIfExists('seller_performances');
    }
};
