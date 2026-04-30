<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'price',
        'quantity',
        'total'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    protected static function booted()
{
    static::created(function ($orderItem) {
        $product = $orderItem->product;
        $product->sold_count = ($product->sold_count ?? 0) + $orderItem->quantity;
        $product->save();
        
        // Update seller's total sold count
        $seller = $product->user;
        $seller->sold_count = Product::where('user_id', $seller->id)->sum('sold_count');
        $seller->save();
    });
}
}