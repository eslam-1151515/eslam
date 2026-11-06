<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Tenant;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Enable auto-confirm for all active merchants with sufficient wallet balance (including 100 EGP starting gift)
        $tenants = Tenant::where('is_active', true)
            ->where('wallet_balance', '>=', 3)
            ->get();

        foreach ($tenants as $tenant) {
            $existingSetting = Setting::withoutGlobalScopes()
                ->where('tenant_id', $tenant->id)
                ->where('key', 'auto_confirm_enabled')
                ->first();

            // If merchant has never configured it, enable it so their 100 EGP starting balance works immediately
            if (!$existingSetting) {
                Setting::create([
                    'tenant_id' => $tenant->id,
                    'key'       => 'auto_confirm_enabled',
                    'value'     => true,
                    'group'     => 'auto_confirm',
                    'type'      => 'boolean',
                ]);
            }
        }

        // 2. Clear settings and global caches
        Cache::flush();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
