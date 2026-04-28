<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'subtotal',
        'discount',
        'total',
        'payment_method',
        'payment_status',
        'order_status',
        'shipping_address',
        'phone',
        'notes'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2'
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function updateStatus($status)
{
    $this->order_status = $status;
    
    switch($status) {
        case 'processing':
            $this->processing_at = now();
            break;
        case 'shipped':
            $this->shipped_at = now();
            break;
        case 'delivered':
            $this->delivered_at = now();
            break;
        case 'cancelled':
            $this->cancelled_at = now();
            break;
    }
    
    $this->save();
    
    // Send email notification
    // Mail::to($this->user->email)->send(new OrderStatusUpdated($this));
}

public function getStatusBadgeAttribute()
{
    $badges = [
        'pending' => 'warning',
        'processing' => 'info',
        'shipped' => 'primary',
        'delivered' => 'success',
        'cancelled' => 'danger'
    ];
    
    $color = $badges[$this->order_status] ?? 'secondary';
    return "<span class='badge bg-{$color}'>{$this->order_status}</span>";
}

public function getStatusTimelineAttribute()
{
    return [
        'pending' => $this->created_at,
        'processing' => $this->processing_at,
        'shipped' => $this->shipped_at,
        'delivered' => $this->delivered_at,
    ];
}
}