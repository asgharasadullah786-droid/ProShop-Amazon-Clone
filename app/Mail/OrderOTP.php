<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderOTP extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $otp;

    public function __construct(Order $order, $otp)
    {
        $this->order = $order;
        $this->otp = $otp;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Order Delivery OTP - ProShop',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-otp',
        );
    }
}