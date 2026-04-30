<?php

namespace App\Mail;

use App\Models\Product;
use App\Models\PriceAlert;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PriceDropAlert extends Mailable
{
    use Queueable, SerializesModels;

    public $product;
    public $alert;

    public function __construct(Product $product, PriceAlert $alert)
    {
        $this->product = $product;
        $this->alert = $alert;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '💰 Price Drop Alert: ' . $this->product->name . ' - ProShop',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.price-drop',
        );
    }
}