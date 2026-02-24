<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderCancelled extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $recipient; // 'customer' or 'admin'

    public function __construct(Order $order, string $recipient = 'customer')
    {
        $this->order = $order;
        $this->recipient = $recipient;
    }

    public function envelope(): Envelope
    {
        $subject = $this->recipient === 'admin' 
            ? "Order #{$this->order->order_number} Cancelled - Admin Notification"
            : "Your Order #{$this->order->order_number} Has Been Cancelled";

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        $view = $this->recipient === 'admin' 
            ? 'emails.order-cancelled-admin'
            : 'emails.order-cancelled-customer';

        return new Content(
            view: $view,
        );
    }

    public function attachments(): array
    {
        return [];
    }
}