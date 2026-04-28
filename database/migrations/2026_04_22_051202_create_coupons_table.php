<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // In xxxx_create_coupons_table.php
Schema::create('coupons', function (Blueprint $table) {
    $table->id();
    $table->string('code')->unique();
    $table->enum('type', ['fixed', 'percent'])->default('fixed');
    $table->decimal('value', 10, 2);
    $table->decimal('min_cart_amount', 10, 2)->nullable();
    $table->integer('usage_limit')->nullable();
    $table->integer('used_count')->default(0);
    $table->dateTime('expires_at')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
