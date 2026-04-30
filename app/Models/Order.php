<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'guest_email',      // ADD THIS
        'guest_name',       // ADD THIS
        'is_guest',         // ADD THIS
        'guest_token',      // ADD THIS
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
        'total' => 'decimal:2',
        'is_guest' => 'boolean',  // ADD THIS
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
    
    // Helper method to check if order is from guest
    public function isGuestOrder()
    {
        return $this->is_guest == true;
    }
    
    // Get customer name (either user or guest)
    public function getCustomerNameAttribute()
    {
        if ($this->is_guest) {
            return $this->guest_name;
        }
        return $this->user?->name ?? 'N/A';
    }
    
    // Get customer email (either user or guest)
    public function getCustomerEmailAttribute()
    {
        if ($this->is_guest) {
            return $this->guest_email;
        }
        return $this->user?->email ?? 'N/A';
    }
}