<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Services\CustomerNotificationService;

class OrderStatusUpdateNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $order;
    public string $oldStatus;
    public string $newStatus;
    public array $data;

    /**
     * Create a new notification instance.
     */
    public function __construct($order, string $oldStatus = '', string $newStatus = '', array $data = [])
    {
        $this->order     = $order;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus ?: (is_object($order) ? ($order->status ?? 'pending') : 'pending');
        $this->data      = $data;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return app(CustomerNotificationService::class)->getEnabledChannels($notifiable, 'order_status_update');
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $orderNumber = is_object($this->order) ? ($this->order->reference_number ?: $this->order->id) : ($this->data['order_number'] ?? '');
        $statusLabel = $this->getStatusLabel($this->newStatus);
        $statusIcon  = $this->getStatusIcon($this->newStatus);
        $storeName   = $this->data['store_name'] ?? 'فاست أوردر';

        return (new MailMessage)
            ->subject("تحديث حالة طلبك #{$orderNumber} إلى [{$statusLabel}] {$statusIcon}")
            ->view('emails.customer.order-status-update', [
                'user'        => $notifiable,
                'order'       => $this->order,
                'orderNumber' => $orderNumber,
                'oldStatus'   => $this->oldStatus,
                'newStatus'   => $this->newStatus,
                'statusLabel' => $statusLabel,
                'statusIcon'  => $statusIcon,
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
        $statusLabel = $this->getStatusLabel($this->newStatus);
        $statusIcon  = $this->getStatusIcon($this->newStatus);

        return [
            'type'         => 'order_status_update',
            'title'        => "تحديث حالة الطلب #{$orderNumber} {$statusIcon}",
            'message'      => "تم تغيير حالة طلبك إلى: {$statusLabel}",
            'order_id'     => $orderId,
            'order_number' => $orderNumber,
            'old_status'   => $this->oldStatus,
            'new_status'   => $this->newStatus,
            'status_label' => $statusLabel,
            'action_url'   => url('/account?order_id=' . $orderId),
            'icon'         => $statusIcon,
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

    /**
     * الحصول على المسمى العربي لحالة الطلب
     */
    private function getStatusLabel(string $status): string
    {
        return match ($status) {
            'pending'    => 'قيد المراجعة',
            'confirmed'  => 'مؤكد',
            'processing' => 'جاري التجهيز',
            'shipped'    => 'في الشحن / في الطريق',
            'delivered'  => 'تم التوصيل بنجاح',
            'cancelled'  => 'ملغي',
            default      => $status,
        };
    }

    /**
     * الحصول على الأيقونة المعبرة عن الحالة
     */
    private function getStatusIcon(string $status): string
    {
        return match ($status) {
            'pending'    => '⏳',
            'confirmed'  => '👍',
            'processing' => '📦',
            'shipped'    => '🚚',
            'delivered'  => '✅',
            'cancelled'  => '❌',
            default      => '🔔',
        };
    }
}
