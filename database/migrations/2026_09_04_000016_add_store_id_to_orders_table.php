<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('store_id')->nullable()->after('user_id')->constrained()->nullOnDelete();
            $table->string('seller_courier', 50)->nullable()->after('notes');
            $table->string('seller_service', 50)->nullable()->after('seller_courier');
            $table->string('seller_tracking_number')->nullable()->after('seller_service');
            $table->timestamp('seller_shipped_at')->nullable()->after('seller_tracking_number');
            $table->decimal('seller_subtotal', 15, 2)->default(0)->after('seller_shipped_at');
            $table->decimal('commission_amount', 15, 2)->default(0)->after('seller_subtotal');
            $table->decimal('seller_earning', 15, 2)->default(0)->after('commission_amount');
            $table->enum('seller_status', ['pending', 'processing', 'shipped', 'delivered', 'completed', 'cancelled'])->default('pending')->after('seller_earning');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['store_id']);
            $table->dropColumn(['store_id', 'seller_courier', 'seller_service', 'seller_tracking_number', 'seller_shipped_at', 'seller_subtotal', 'commission_amount', 'seller_earning', 'seller_status']);
        });
    }
};
