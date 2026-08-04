<?php
namespace App\Console\Commands;

use App\Services\CacheService;
use Illuminate\Console\Command;

class ClearTenantCache extends Command
{
    protected $signature = 'cache:clear-tenant {tenant_id}';
    protected $description = 'مسح كل الـ cache الخاص بمتجر معين';

    public function handle(): void
    {
        $tenantId = (int) $this->argument('tenant_id');
        CacheService::invalidateAll($tenantId);
        $this->info("✅ تم مسح cache المتجر #{$tenantId} بنجاح.");
    }
}
