<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionExpiryReminderMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $merchantName,
        public readonly string $storeName,
        public readonly int $daysRemaining,
        public readonly string $renewUrl
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "تنبيه: اشتراكك في فاست أوردر ينتهي خلال {$this->daysRemaining} أيام"
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.subscription.expiry-reminder');
    }
}
