<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // database/seeders/CoupleSeeder.php
public function run()
{
    \App\Models\Coupon::create([
        'code' => 'SAVE10',
        'type' => 'percent',
        'value' => 10,
        'min_cart_amount' => 50,
        'usage_limit' => 100,
        'expires_at' => now()->addDays(30),
        'is_active' => true
    ]);
    
    \App\Models\Coupon::create([
        'code' => 'FLAT20',
        'type' => 'fixed',
        'value' => 20,
        'min_cart_amount' => 100,
        'usage_limit' => 50,
        'expires_at' => now()->addDays(15),
        'is_active' => true
    ]);
}
}
