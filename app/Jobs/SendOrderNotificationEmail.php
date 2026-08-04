<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendOrderNotificationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 60;

    public function __construct(
        public readonly int $orderId,
        public readonly string $recipientEmail,
        public readonly string $type // 'new_order', 'status_update', 'confirmed'
    ) {}

    public function handle(): void
    {
        Log::info("[Queue] Sending {$this->type} email for order #{$this->orderId} to {$this->recipientEmail}");
        // سيتم ربطه بـ Mail classes في المرحلة 40
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("[Queue] Failed to send email for order #{$this->orderId}: " . $exception->getMessage());
    }
}
