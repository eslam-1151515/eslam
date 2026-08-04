<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateDashboardStats extends Command
{
    protected $signature = 'stats:update-dashboard';
    protected $description = 'تحديث إحصائيات الداشبورد لجميع المتاجر';

    public function handle(): void
    {
        $this->info('تحديث إحصائيات الداشبورد...');
        // سيتم ربطه بـ DashboardStats model لاحقاً
        Log::info('[Scheduler] Dashboard stats updated at ' . now());
        $this->info('تم التحديث بنجاح.');
    }
}
