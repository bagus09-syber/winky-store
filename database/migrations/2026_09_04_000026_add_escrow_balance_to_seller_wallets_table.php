<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seller_wallets', function (Blueprint $table) {
            $table->decimal('escrow_balance', 15, 2)->default(0)->after('available_balance');
        });
    }

    public function down(): void
    {
        Schema::table('seller_wallets', function (Blueprint $table) {
            $table->dropColumn('escrow_balance');
        });
    }
};
