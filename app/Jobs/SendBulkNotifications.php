<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendBulkNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 300;

    public function __construct(
        public readonly int $tenantId,
        public readonly array $recipientIds,
        public readonly string $title,
        public readonly string $message
    ) {}

    public function handle(): void
    {
        Log::info("[Queue] Sending bulk notifications to " . count($this->recipientIds) . " recipients for tenant #{$this->tenantId}");
    }
}
