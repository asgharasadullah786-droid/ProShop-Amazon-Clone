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
        Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->string('order_number')->unique();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->decimal('subtotal', 10, 2);
    $table->decimal('discount', 10, 2)->default(0);
    $table->decimal('total', 10, 2);
    $table->string('payment_method')->default('cod'); // cod, card, mobile_banking
    $table->string('payment_status')->default('pending'); // pending, paid, failed
    $table->string('order_status')->default('pending'); // pending, processing, shipped, delivered, cancelled
    $table->text('shipping_address');
    $table->string('phone');
    $table->text('notes')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
