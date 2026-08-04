<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Services\CustomerNotificationService;

class WishlistBackInStockNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $product;

    /**
     * Create a new notification instance.
     */
    public function __construct($product)
    {
        $this->product = $product;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return app(CustomerNotificationService::class)->getEnabledChannels($notifiable, 'wishlist_back_in_stock');
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $productName = is_object($this->product) ? ($this->product->name ?? 'منتجك المفضل') : (is_array($this->product) ? ($this->product['name'] ?? 'منتجك المفضل') : 'منتجك المفضل');
        $productId   = is_object($this->product) ? ($this->product->id ?? '') : (is_array($this->product) ? ($this->product['id'] ?? '') : '');
        $actionUrl   = url('/product?id=' . $productId);

        return (new MailMessage)
            ->subject("خبر سار! \"{$productName}\" عاد للتوفر في المخزون! 💖")
            ->view('emails.customer.wishlist-back-in-stock', [
                'user'        => $notifiable,
                'product'     => $this->product,
                'productName' => $productName,
                'actionUrl'   => $actionUrl,
            ]);
    }

    /**
     * Get the array representation of the notification for database storage.
     */
    public function toDatabase(object $notifiable): array
    {
        $productName  = is_object($this->product) ? ($this->product->name ?? 'منتجك المفضل') : (is_array($this->product) ? ($this->product['name'] ?? 'منتجك المفضل') : 'منتجك المفضل');
        $productId    = is_object($this->product) ? ($this->product->id ?? null) : (is_array($this->product) ? ($this->product['id'] ?? null) : null);
        $productPrice = is_object($this->product) ? ($this->product->price ?? null) : (is_array($this->product) ? ($this->product['price'] ?? null) : null);

        return [
            'type'         => 'wishlist_back_in_stock',
            'title'        => "منتج في قائمة أمنياتك متاح الآن! 💖",
            'message'      => "المنتج \"{$productName}\" عاد للمخزون ومتاح للطلب الآن قبل نفاد الكمية.",
            'product_id'   => $productId,
            'product_name' => $productName,
            'price'        => $productPrice,
            'action_url'   => url('/product?id=' . $productId),
            'icon'         => '💖',
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
