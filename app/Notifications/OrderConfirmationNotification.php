<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Services\CustomerNotificationService;

class OrderConfirmationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $order;
    public array $data;

    /**
     * Create a new notification instance.
     */
    public function __construct($order, array $data = [])
    {
        $this->order = $order;
        $this->data  = $data;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return app(CustomerNotificationService::class)->getEnabledChannels($notifiable, 'order_confirmation');
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $orderNumber = is_object($this->order) ? ($this->order->reference_number ?: $this->order->id) : ($this->data['order_number'] ?? '');
        $storeName = $this->data['store_name'] ?? 'فاست أوردر';

        return (new MailMessage)
            ->subject("تأكيد استلام طلبك #{$orderNumber} من {$storeName} 📦")
            ->view('emails.customer.order-confirmation', [
                'user'        => $notifiable,
                'order'       => $this->order,
                'orderNumber' => $orderNumber,
                'storeName'   => $storeName,
                'data'        => $this->data,
                'actionUrl'   => url('/account?order_id=' . (is_object($this->order) ? $this->order->id : '')),
            ]);
    }

    /**
     * Get the array representation of the notification for database storage.
     */
    public function toDatabase(object $notifiable): array
    {
        $orderId     = is_object($this->order) ? $this->order->id : ($this->data['order_id'] ?? null);
        $orderNumber = is_object($this->order) ? ($this->order->reference_number ?: $this->order->id) : ($this->data['order_number'] ?? '');
        $total       = is_object($this->order) ? $this->order->total : ($this->data['total'] ?? 0);

        return [
            'type'         => 'order_confirmation',
            'title'        => "تم استلام طلبك #{$orderNumber} بنجاح 📦",
            'message'      => "طلبك رقم #{$orderNumber} قيد التجهيز والمراجعة الآن.",
            'order_id'     => $orderId,
            'order_number' => $orderNumber,
            'total'        => round((float) $total, 2),
            'action_url'   => url('/account?order_id=' . $orderId),
            'icon'         => '📦',
            'created_at'   => now()->toIso8601String(),
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
