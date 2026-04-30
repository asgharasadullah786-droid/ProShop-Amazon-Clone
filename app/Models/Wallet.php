<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = ['user_id', 'balance'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function credit($amount, $description = null, $reference = null)
    {
        $this->balance += $amount;
        $this->save();

        WalletTransaction::create([
            'user_id' => $this->user_id,
            'type' => 'credit',
            'amount' => $amount,
            'balance_after' => $this->balance,
            'description' => $description,
            'reference' => $reference,
        ]);

        return true;
    }

    public function debit($amount, $description = null, $reference = null, $orderId = null)
    {
        if ($this->balance < $amount) {
            return false;
        }

        $this->balance -= $amount;
        $this->save();

        WalletTransaction::create([
            'user_id' => $this->user_id,
            'order_id' => $orderId,
            'type' => 'debit',
            'amount' => $amount,
            'balance_after' => $this->balance,
            'description' => $description,
            'reference' => $reference,
        ]);

        return true;
    }

    public function hasSufficientBalance($amount)
    {
        return $this->balance >= $amount;
    }
}