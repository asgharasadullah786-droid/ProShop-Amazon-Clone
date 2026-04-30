<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbandonedCart extends Model
{
    protected $fillable = [
        'user_id', 'session_id', 'cart_data', 'email', 'name',
        'cart_total', 'item_count', 'last_activity', 
        'reminder_sent', 'reminder_sent_at', 'recovered'
    ];
    
    protected $casts = [
        'cart_data' => 'array',
        'last_activity' => 'datetime',
        'reminder_sent_at' => 'datetime',
        'recovered' => 'boolean',
        'reminder_sent' => 'boolean'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function isAbandoned()
    {
        // Cart abandoned if no activity for more than 1 hour
        return $this->last_activity->diffInHours(now()) >= 1;
    }
    
    public function shouldSendReminder()
    {
        return $this->isAbandoned() && !$this->reminder_sent && !$this->recovered;
    }
}