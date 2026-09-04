<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('affiliate_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('affiliate_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('code', 16);

            $table->index('affiliate_id');
            $table->index('code');
            $table->index('product_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliate_links');
    }
};