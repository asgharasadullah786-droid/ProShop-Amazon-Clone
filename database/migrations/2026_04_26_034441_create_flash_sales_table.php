<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('flash_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->decimal('sale_price', 10, 2);
            $table->integer('sale_quantity')->default(0);
            $table->datetime('start_time');
            $table->datetime('end_time');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('flash_sales');
    }
};