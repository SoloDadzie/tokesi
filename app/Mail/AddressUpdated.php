<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AddressUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $recipientEmail;
    public $emailType; // 'old', 'new', or 'current'

    public function __construct(Order $order, string $recipientEmail, string $emailType = 'current')
    {
        $this->order = $order;
        $this->recipientEmail = $recipientEmail;
        $this->emailType = $emailType;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Delivery Address Updated for Order #{$this->order->order_number}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.address-updated',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}