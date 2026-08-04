<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeMerchantMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $merchantName,
        public readonly string $storeName,
        public readonly string $storeUrl
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "مرحباً بك في فاست أوردر - متجرك {$this->storeName} جاهز!"
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.merchant.welcome'
        );
    }
}
