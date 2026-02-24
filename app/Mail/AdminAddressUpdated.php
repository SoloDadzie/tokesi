<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminAddressUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $oldEmail;

    public function __construct(Order $order, string $oldEmail)
    {
        $this->order = $order;
        $this->oldEmail = $oldEmail;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Address Updated for Order #{$this->order->order_number} - Admin Alert",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-address-updated',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}