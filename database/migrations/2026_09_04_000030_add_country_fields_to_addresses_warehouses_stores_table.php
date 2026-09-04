<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->string('country_code', 2)->default('ID')->after('recipient_name');
            $table->string('country_name')->default('Indonesia')->after('country_code');
        });

        Schema::table('warehouses', function (Blueprint $table) {
            $table->string('country_code', 2)->default('ID')->after('contact_name');
        });

        Schema::table('stores', function (Blueprint $table) {
            $table->string('country_code', 2)->default('ID')->after('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropColumn(['country_code', 'country_name']);
        });

        Schema::table('warehouses', function (Blueprint $table) {
            $table->dropColumn('country_code');
        });

        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('country_code');
        });
    }
};
