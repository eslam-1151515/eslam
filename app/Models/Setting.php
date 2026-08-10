<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;
use App\Services\SettingService;

class Setting extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'key',
        'value',
        'group',
        'type',
    ];

    /**
     * Get a setting value by key (delegates to SettingService for multi-level fallback)
     */
    public static function get(string $key, $default = null, $tenantId = null)
    {
        return app(SettingService::class)->get($key, $default, $tenantId);
    }

    /**
     * Set a setting value by key (delegates to SettingService for multi-level storage)
     */
    public static function set(string $key, $value, string $group = 'general', $tenantId = null): void
    {
        app(SettingService::class)->set($key, $value, $group, $tenantId);
    }

    /**
     * Get a global platform setting (tenant_id is null)
     */
    public static function getGlobal(string $key, $default = null)
    {
        return app(SettingService::class)->getGlobal($key, $default);
    }

    /**
     * Set a global platform setting (tenant_id is null)
     */
    public static function setGlobal(string $key, $value, string $group = 'general'): void
    {
        app(SettingService::class)->setGlobal($key, $value, $group);
    }
}
