<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckExpiredSubscriptions extends Command
{
    protected $signature = 'subscriptions:check-expired';
    protected $description = 'فحص الاشتراكات المنتهية وتعليق المتاجر المنتهية';

    public function handle(): void
    {
        $this->info('فحص الاشتراكات المنتهية...');
        // سيتم ربطه بـ Subscription model لاحقاً
        Log::info('[Scheduler] Checked expired subscriptions at ' . now());
        $this->info('تم الفحص بنجاح.');
    }
}
