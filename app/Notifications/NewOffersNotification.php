<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Services\CustomerNotificationService;

class NewOffersNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public array $offerData;

    /**
     * Create a new notification instance.
     */
    public function __construct(array $offerData)
    {
        $this->offerData = $offerData;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return app(CustomerNotificationService::class)->getEnabledChannels($notifiable, 'new_offers');
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $title     = $this->offerData['title'] ?? 'عروض جديدة وخصومات حصرية من فاست أوردر! 🏷️';
        $storeName = $this->offerData['store_name'] ?? 'فاست أوردر';
        $url       = $this->offerData['url'] ?? url('/products');

        return (new MailMessage)
            ->subject($title)
            ->view('emails.customer.new-offers', [
                'user'      => $notifiable,
                'offer'     => $this->offerData,
                'title'     => $title,
                'storeName' => $storeName,
                'actionUrl' => $url,
            ]);
    }

    /**
     * Get the array representation of the notification for database storage.
     */
    public function toDatabase(object $notifiable): array
    {
        $title = $this->offerData['title'] ?? 'عروض جديدة حصرية! 🏷️';
        $desc  = $this->offerData['description'] ?? 'لا تفوت أحدث عروضنا وخصوماتنا الحصرية المتاحة الآن.';
        $url   = $this->offerData['url'] ?? url('/products');

        return [
            'type'                => 'new_offers',
            'title'               => $title,
            'message'             => $desc,
            'discount_percentage' => $this->offerData['discount'] ?? null,
            'coupon_code'         => $this->offerData['coupon_code'] ?? null,
            'image'               => $this->offerData['image'] ?? null,
            'action_url'          => $url,
            'icon'                => '🏷️',
            'created_at'          => now()->toIso8601String(),
        ];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
