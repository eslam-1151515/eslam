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

        // Invalidate key cache
        if ($tenantId) {
            Cache::forget("setting:tenant:{$tenantId}:{$key}");
            Cache::forget("setting:group:{$group}:{$tenantId}");
        } else {
            Cache::forget("setting:global:{$key}");
            Cache::forget("setting:group:{$group}:global");
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
}
