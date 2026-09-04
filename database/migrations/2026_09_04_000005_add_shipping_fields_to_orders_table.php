<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('shipping_courier')->nullable()->after('shipping_cost');
            $table->string('shipping_service')->nullable()->after('shipping_courier');
            $table->unsignedInteger('shipping_estimated_days')->nullable()->after('shipping_service');
            $table->string('tracking_number')->nullable()->after('shipping_estimated_days');
            $table->timestamp('shipped_at')->nullable()->after('tracking_number');
            $table->timestamp('delivered_at')->nullable()->after('shipped_at');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_courier', 'shipping_service', 'shipping_estimated_days',
                'tracking_number', 'shipped_at', 'delivered_at',
            ]);
        });
    }
};
