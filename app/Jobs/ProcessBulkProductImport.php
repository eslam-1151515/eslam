<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessBulkProductImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;
    public int $timeout = 600; // 10 دقائق للملفات الكبيرة

    public function __construct(
        public readonly string $filePath,
        public readonly int $tenantId,
        public readonly int $userId
    ) {}

    public function handle(): void
    {
        Log::info("[Queue] Processing bulk import for tenant #{$this->tenantId}");
        // منطق الاستيراد الجماعي - يُربط مع BulkUpload controller
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("[Queue] Bulk import failed for tenant #{$this->tenantId}: " . $exception->getMessage());
        // يمكن إرسال إشعار للمستخدم هنا
    }
}
