<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewOrderMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly array $orderData,
        public readonly string $recipientType // 'merchant' or 'customer'
    ) {}

    public function envelope(): Envelope
    {
        $subject = $this->recipientType === 'merchant'
            ? "طلب جديد #" . $this->orderData['id'] . " - " . $this->orderData['store_name']
            : "تأكيد طلبك #" . $this->orderData['id'] . " من " . $this->orderData['store_name'];

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        $view = $this->recipientType === 'merchant'
            ? 'emails.orders.new-order-merchant'
            : 'emails.orders.new-order-customer';

        return new Content(view: $view);
    }
}
