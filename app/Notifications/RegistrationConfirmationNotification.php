<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Services\CustomerNotificationService;

class RegistrationConfirmationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public array $data;

    /**
     * Create a new notification instance.
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return app(CustomerNotificationService::class)->getEnabledChannels($notifiable, 'registration_confirmation');
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $userName = $notifiable->name ?? ($this->data['name'] ?? 'عميلنا العزيز');
        $storeName = $this->data['store_name'] ?? 'فاست أوردر';

        return (new MailMessage)
            ->subject("مرحباً بك في {$storeName}! 🎉 تأكيد التسجيل")
            ->view('emails.customer.registration-confirmation', [
                'user'      => $notifiable,
                'userName'  => $userName,
                'storeName' => $storeName,
                'data'      => $this->data,
                'actionUrl' => url('/account'),
            ]);
    }

    /**
     * Get the array representation of the notification for database storage.
     */
    public function toDatabase(object $notifiable): array
    {
        $storeName = $this->data['store_name'] ?? 'فاست أوردر';

        return [
            'type'       => 'registration_confirmation',
            'title'      => "مرحباً بك في {$storeName} 🎉",
            'message'    => 'تم إنشاء وتأكيد حسابك بنجاح. نتمنى لك تجربة تسوق رائعة!',
            'action_url' => url('/account'),
            'icon'       => '🎉',
            'created_at' => now()->toIso8601String(),
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
