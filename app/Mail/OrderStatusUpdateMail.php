<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdateMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly array $orderData,
        public readonly string $newStatus,
        public readonly string $statusLabel
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "تحديث على طلبك #" . $this->orderData['id'] . " - " . $this->statusLabel
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.orders.status-update');
    }
}
