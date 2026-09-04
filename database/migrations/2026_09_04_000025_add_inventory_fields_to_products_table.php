<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('barcode')->nullable()->after('sku');
            $table->unsignedInteger('reserved_stock')->default(0)->after('stock');
            $table->unsignedInteger('low_stock_threshold')->nullable()->after('reserved_stock');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['barcode', 'reserved_stock', 'low_stock_threshold']);
        });
    }
};
