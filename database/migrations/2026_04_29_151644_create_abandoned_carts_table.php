<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('abandoned_carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('session_id');
            $table->text('cart_data');
            $table->string('email')->nullable();
            $table->string('name')->nullable();
            $table->decimal('cart_total', 10, 2);
            $table->integer('item_count');
            $table->timestamp('last_activity');
            $table->boolean('reminder_sent')->default(false);
            $table->timestamp('reminder_sent_at')->nullable();
            $table->boolean('recovered')->default(false);
            $table->timestamps();
            
            $table->index(['session_id', 'last_activity']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('abandoned_carts');
    }
};