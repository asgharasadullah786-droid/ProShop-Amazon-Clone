<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('delivery_otp', 6)->nullable()->after('notes');
            $table->timestamp('otp_expires_at')->nullable()->after('delivery_otp');
            $table->boolean('otp_verified')->default(false)->after('otp_expires_at');
            $table->integer('otp_attempts')->default(0)->after('otp_verified');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['delivery_otp', 'otp_expires_at', 'otp_verified', 'otp_attempts']);
        });
    }
};