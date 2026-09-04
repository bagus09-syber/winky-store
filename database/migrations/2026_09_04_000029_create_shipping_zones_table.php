<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_zones', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 10)->unique();
            $table->text('description')->nullable();
            $table->decimal('base_rate', 15, 2)->default(0);
            $table->decimal('per_kg_rate', 15, 2)->default(0);
            $table->unsignedInteger('estimated_days_min')->default(1);
            $table->unsignedInteger('estimated_days_max')->default(7);
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('shipping_zone_countries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipping_zone_id')->constrained()->cascadeOnDelete();
            $table->string('country_code', 2);
            $table->string('country_name');
            $table->timestamps();

            $table->unique(['shipping_zone_id', 'country_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_zone_countries');
        Schema::dropIfExists('shipping_zones');
    }
};
