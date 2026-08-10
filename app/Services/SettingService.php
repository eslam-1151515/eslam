<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingService
{
    /**
     * Get a setting value.
     * Looks up tenant setting first (if tenantId is provided or in active context).
     * Falls back to global setting (tenant_id = null) if not found.
     * Falls back to $default if still not found.
     */
    public function get(string $key, $default = null, $tenantId = null)
    {
        if ($tenantId === null) {
            $tenantId = session()->get('tenant_id') ?? config('tenant.id');
        }

        // Direct DB lookup without cache for critical dynamic keys (Payment & Contact settings)
        $uncachedKeys = ['vodafone_cash_number', 'instapay_number', 'instapay_address', 'support_phone', 'phone'];
        if (in_array($key, $uncachedKeys, true)) {
            if ($tenantId) {
                $tenantSetting = Setting::withoutGlobalScopes()
                    ->where('tenant_id', $tenantId)
                    ->where('key', $key)
                    ->first();
                if ($tenantSetting !== null && $tenantSetting->value !== null && $tenantSetting->value !== '') {
                    return $tenantSetting->value;
                }
            }

            $globalSetting = Setting::withoutGlobalScopes()
                ->whereNull('tenant_id')
                ->where('key', $key)
                ->first();

            if ($globalSetting !== null && $globalSetting->value !== null && $globalSetting->value !== '') {
                return $globalSetting->value;
            }

            return $default;
        }

        // 1. Try to find the tenant-specific setting
        if ($tenantId) {
            $cacheKey = "setting:tenant:{$tenantId}:{$key}";
            $tenantValue = Cache::remember($cacheKey, 7200, function () use ($tenantId, $key) {
                $tenantSetting = Setting::withoutGlobalScopes()
                    ->where('tenant_id', $tenantId)
                    ->where('key', $key)
                    ->first();

                return $tenantSetting !== null ? $tenantSetting->value : '__NOT_FOUND__';
            });

            if ($tenantValue !== '__NOT_FOUND__') {
                return $tenantValue;
            }
        }

        // 2. Fall back to global setting (tenant_id is null)
        $globalCacheKey = "setting:global:{$key}";
        $globalValue = Cache::remember($globalCacheKey, 7200, function () use ($key) {
            $globalSetting = Setting::withoutGlobalScopes()
                ->whereNull('tenant_id')
                ->where('key', $key)
                ->first();

            return $globalSetting !== null ? $globalSetting->value : '__NOT_FOUND__';
        });

        if ($globalValue !== '__NOT_FOUND__') {
            return $globalValue;
        }

        return $default;
    }

    /**
     * Set/Save a setting value.
     */
    public function set(string $key, $value, string $group = 'general', $tenantId = null): Setting
    {
        if ($tenantId === null) {
            $tenantId = session()->get('tenant_id') ?? config('tenant.id');
        }

        $setting = Setting::withoutGlobalScopes()->updateOrCreate(
            [
                'tenant_id' => $tenantId,
                'key' => $key,
            ],
            [
                'value' => $value,
                'group' => $group,
            ]
        );

        // Invalidate all key & group caches for both global and tenant contexts
        Cache::forget("setting:global:{$key}");
        Cache::forget("setting:group:{$group}:global");
        if ($tenantId) {
            Cache::forget("setting:tenant:{$tenantId}:{$key}");
            Cache::forget("setting:group:{$group}:{$tenantId}");
        }

        return $setting;
    }

    /**
     * Fetch all settings in a group, merging tenant settings on top of global settings.
     */
    public function getGroup(string $group, $tenantId = null): array
    {
        if ($tenantId === null) {
            $tenantId = session()->get('tenant_id') ?? config('tenant.id');
        }

        // 1. Fetch global settings for this group with cache
        $globalCacheKey = "setting:group:{$group}:global";
        $globalSettings = Cache::remember($globalCacheKey, 7200, function () use ($group) {
            return Setting::withoutGlobalScopes()
                ->whereNull('tenant_id')
                ->where('group', $group)
                ->get()
                ->pluck('value', 'key')
                ->toArray();
        });

        // 2. Fetch tenant settings for this group with cache
        $tenantSettings = [];
        if ($tenantId) {
            $tenantCacheKey = "setting:group:{$group}:{$tenantId}";
            $tenantSettings = Cache::remember($tenantCacheKey, 7200, function () use ($group, $tenantId) {
                return Setting::withoutGlobalScopes()
                    ->where('tenant_id', $tenantId)
                    ->where('group', $group)
                    ->get()
                    ->pluck('value', 'key')
                    ->toArray();
            });
        }

        // Merge tenant settings on top of global settings
        return array_merge($globalSettings, $tenantSettings);
    }

    /**
     * Get a global platform setting value (tenant_id is strictly null).
     */
    public function getGlobal(string $key, $default = null)
    {
        $globalValue = \Illuminate\Support\Facades\DB::table('settings')
            ->whereNull('tenant_id')
            ->where('key', $key)
            ->value('value');

        if ($globalValue !== null && $globalValue !== '') {
            return $globalValue;
        }

        return $default;
    }

    /**
     * Set/Save a global platform setting value (tenant_id is strictly null).
     */
    public function setGlobal(string $key, $value, string $group = 'general')
    {
        // First delete any old global setting key or tenant-specific duplicates for this platform key
        \Illuminate\Support\Facades\DB::table('settings')
            ->where('key', $key)
            ->delete();

        \Illuminate\Support\Facades\DB::table('settings')->insert([
            'tenant_id'  => null,
            'key'        => $key,
            'value'      => $value,
            'group'      => $group,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Cache::forget("setting:global:{$key}");
        Cache::forget("setting:group:{$group}:global");
    }
}
