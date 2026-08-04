<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Services\CustomerNotificationService;

class CartAbandonmentReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $cart;
    public array $data;

    /**
     * Create a new notification instance.
     */
    public function __construct($cart = null, array $data = [])
    {
        $this->cart = $cart;
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return app(CustomerNotificationService::class)->getEnabledChannels($notifiable, 'cart_abandonment_reminder');
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $itemsCount = $this->data['items_count'] ?? (is_object($this->cart) ? ($this->cart->items_count ?? 1) : 1);
        $storeName  = $this->data['store_name'] ?? 'فاست أوردر';
        $actionUrl  = $this->data['checkout_url'] ?? url('/checkout');

        return (new MailMessage)
            ->subject("هل نسيت شيئاً في سلتك في {$storeName}؟ 🛒 أكمل طلبك الآن")
            ->view('emails.customer.cart-abandonment', [
                'user'       => $notifiable,
                'cart'       => $this->cart,
                'itemsCount' => $itemsCount,
                'storeName'  => $storeName,
                'data'       => $this->data,
                'actionUrl'  => $actionUrl,
            ]);
    }

    /**
     * Get the array representation of the notification for database storage.
     */
    public function toDatabase(object $notifiable): array
    {
        $itemsCount = $this->data['items_count'] ?? (is_object($this->cart) ? ($this->cart->items_count ?? 1) : 1);
        $cartId     = is_object($this->cart) ? $this->cart->id : ($this->data['cart_id'] ?? null);

        return [
            'type'        => 'cart_abandonment_reminder',
            'title'       => 'سلة تسوقك في انتظارك 🛒',
            'message'     => 'لديك منتجات رائعة في سلتك لم تكتمل عملية شرائها بعد. أكمل طلبك الآن قبل نفاد المخزون!',
            'cart_id'     => $cartId,
            'items_count' => $itemsCount,
            'total'       => $this->data['total'] ?? null,
            'action_url'  => $this->data['checkout_url'] ?? url('/checkout'),
            'icon'        => '🛒',
            'created_at'  => now()->toIso8601String(),
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
