<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockNotification extends Model
{
    protected $fillable = [
        'product_id', 'email', 'name', 'is_sent', 'sent_at', 'unsubscribe_token'
    ];

    protected $casts = [
        'is_sent' => 'boolean',
        'sent_at' => 'datetime'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public static function generateToken()
    {
        return bin2hex(random_bytes(32));
    }
}