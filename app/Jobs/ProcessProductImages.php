<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProcessProductImages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 120;

    public function __construct(
        public readonly string $imagePath,
        public readonly int $tenantId
    ) {}

    public function handle(): void
    {
        try {
            // إذا كانت مكتبة Intervention Image موجودة
            if (class_exists('\Intervention\Image\Facades\Image')) {
                $fullPath = Storage::path($this->imagePath);
                $image = \Intervention\Image\Facades\Image::make($fullPath);

                // إنشاء thumbnail (300x300)
                $thumbPath = str_replace('/products/', '/products/thumbs/', $this->imagePath);
                $image->fit(300, 300)->save(Storage::path($thumbPath));

                // ضغط الصورة الأصلية
                $image->save(Storage::path($this->imagePath), 80);

                Log::info("[Queue] Processed image: {$this->imagePath}");
            } else {
                Log::info("[Queue] Image processing skipped - Intervention Image not installed");
            }
        } catch (\Exception $e) {
            Log::error("[Queue] Image processing failed: " . $e->getMessage());
        }
    }
}
