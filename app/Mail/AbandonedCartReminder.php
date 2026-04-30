<?php

namespace App\Mail;

use App\Models\AbandonedCart;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AbandonedCartReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $abandonedCart;
    public $discountCode;

    public function __construct(AbandonedCart $abandonedCart)
    {
        $this->abandonedCart = $abandonedCart;
        // Generate a 10% discount coupon
        $this->discountCode = 'SAVE10_' . strtoupper(substr(uniqid(), -6));
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'You left something in your cart! 🛒 - ProShop',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.abandoned-cart',
        );
    }
}