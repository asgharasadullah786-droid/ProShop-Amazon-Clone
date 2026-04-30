<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceAlert extends Model
{
    protected $fillable = [
        'user_id', 'product_id', 'email', 'name', 
        'desired_price', 'current_price', 'is_sent', 'sent_at', 'unsubscribe_token'
    ];

    protected $casts = [
        'is_sent' => 'boolean',
        'sent_at' => 'datetime',
        'desired_price' => 'decimal:2',
        'current_price' => 'decimal:2'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generateToken()
    {
        return bin2hex(random_bytes(32));
    }
}