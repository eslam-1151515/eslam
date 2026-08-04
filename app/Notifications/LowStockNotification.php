<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Product;

class LowStockNotification extends Notification
{
    use Queueable;

    public Product $product;
    public int $currentStock;

    public function __construct(Product $product, int $currentStock)
    {
        $this->product = $product;
        $this->currentStock = $currentStock;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'low_stock_warning',
            'title' => '⚠️ تنبيه: انخفاض كمية المخزون!',
            'message' => "المنتج \"{$this->product->name}\" أوشك على النفاد. الكمية المتبقية: {$this->currentStock} (الحد المسموح: {$this->product->low_stock_threshold})",
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'current_stock' => $this->currentStock,
            'threshold' => $this->product->low_stock_threshold,
            'action_url' => "/admin/products/{$this->product->id}/edit",
            'icon' => '⚠️',
            'created_at' => now()->toIso8601String(),
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
