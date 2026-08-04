<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CleanupTempStorage extends Command
{
    protected $signature = 'storage:cleanup-temp';
    protected $description = 'تنظيف الملفات المؤقتة القديمة';

    public function handle(): void
    {
        $this->info('تنظيف الملفات المؤقتة...');
        $files = Storage::files('temp');
        $deleted = 0;
        foreach ($files as $file) {
            if (Storage::lastModified($file) < now()->subDays(7)->timestamp) {
                Storage::delete($file);
                $deleted++;
            }
        }
        Log::info("[Scheduler] Cleaned up {$deleted} temp files");
        $this->info("تم حذف {$deleted} ملف مؤقت.");
    }
}
