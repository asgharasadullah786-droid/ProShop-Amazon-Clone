<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlashSale extends Model
{
    protected $fillable = [
        'product_id', 'sale_price', 'sale_quantity', 'start_time', 'end_time'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    public function isActive()
    {
        return now()->between($this->start_time, $this->end_time);
    }
}