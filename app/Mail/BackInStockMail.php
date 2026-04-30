<?php

namespace App\Mail;

use App\Models\Product;
use App\Models\StockNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BackInStockMail extends Mailable
{
    use Queueable, SerializesModels;

    public $product;
    public $notification;

    public function __construct(Product $product, StockNotification $notification)
    {
        $this->product = $product;
        $this->notification = $notification;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✓ Back in Stock: ' . $this->product->name . ' - ProShop',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.back-in-stock',
        );
    }
}