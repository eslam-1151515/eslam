<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckExpiredSubscriptions extends Command
{
    protected $signature = 'subscriptions:check-expired';
    protected $description = 'فحص الاشتراكات المنتهية وتعليق المتاجر المنتهية تلقائياً';

    public function handle(): void
    {
        $this->info('جاري فحص الاشتراكات المنتهية...');
        
        $expiredTenants = \App\Models\Tenant::where('is_active', true)
            ->whereNotNull('subscription_ends_at')
            ->where('subscription_ends_at', '<', now())
            ->get();

        $count = 0;
        foreach ($expiredTenants as $tenant) {
            $activeSub = $tenant->subscriptions()->where('status', 'active')->latest()->first();
            $isCommission = $activeSub && ($activeSub->plan?->slug === 'commission' || str_contains($activeSub->plan?->name ?? '', 'عمولة'));

            if (!$isCommission) {
                $tenant->update([
                    'is_active' => false,
                    'subscription_status' => 'expired',
                ]);
                $count++;
            }
        }

        \Illuminate\Support\Facades\Log::info("[Scheduler] Checked expired subscriptions: deactivated {$count} tenants.");
        $this->info("تم فحص المتاجر وإيقاف {$count} متاجر منتهية الاشتراك بنجاح.");
    }
}
