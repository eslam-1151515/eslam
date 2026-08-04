<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\AbandonedCart;

class AbandonedCartRecoveryMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $mailLocale;

    public function __construct(
        public readonly AbandonedCart $abandonedCart,
        public readonly ?string $discountCode = null,
        public readonly ?float $discountPercentage = null,
        string $mailLocale = 'ar'
    ) {
        $this->mailLocale = $mailLocale;
    }

    public function envelope(): Envelope
    {
        $tenantName = $this->abandonedCart->tenant?->name ?? 'متجرنا';
        
        $subject = $this->mailLocale === 'en'
            ? "You left something behind! Complete your order at {$tenantName}"
            : "لقد نسيت بعض المنتجات في سلتك! أكمل طلبك الآن من {$tenantName}";

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.abandoned-cart-recovery',
            with: [
                'items' => $this->abandonedCart->cart_data['items'] ?? [],
                'subtotal' => $this->abandonedCart->cart_data['subtotal'] ?? 0,
                'total' => $this->abandonedCart->cart_data['total'] ?? 0,
                'recoveryUrl' => url('/shop/cart/recover/' . $this->abandonedCart->recovery_token),
                'discountCode' => $this->discountCode,
                'discountPercentage' => $this->discountPercentage,
                'locale' => $this->mailLocale,
                'storeName' => $this->abandonedCart->tenant?->name ?? 'متجرنا',
            ]
        );
    }
}
