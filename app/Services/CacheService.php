<?php
namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CacheService
{
    /**
     * مدة الـ Cache الافتراضية بالثواني
     */
    private const DEFAULT_TTL = 3600; // ساعة واحدة
    private const SETTINGS_TTL = 7200; // ساعتين
    private const STATS_TTL = 1800;   // 30 دقيقة
    private const PRODUCTS_TTL = 3600; // ساعة

    /**
     * توليد مفتاح cache خاص بكل tenant
     */
    public static function tenantKey(int $tenantId, string $key): string
    {
        return "tenant:{$tenantId}:{$key}";
    }

    /**
     * الحصول على إعدادات المتجر مع Cache
     */
    public static function getSettings(int $tenantId, callable $callback): mixed
    {
        $cacheKey = self::tenantKey($tenantId, 'settings');
        return Cache::remember($cacheKey, self::SETTINGS_TTL, $callback);
    }

    /**
     * مسح cache الإعدادات عند التعديل
     */
    public static function invalidateSettings(int $tenantId): void
    {
        Cache::forget(self::tenantKey($tenantId, 'settings'));
        Log::info("[Cache] Settings cache invalidated for tenant #{$tenantId}");
    }

    /**
     * الحصول على الأقسام مع Cache
     */
    public static function getCategories(int $tenantId, callable $callback): mixed
    {
        $cacheKey = self::tenantKey($tenantId, 'categories');
        return Cache::remember($cacheKey, self::PRODUCTS_TTL, $callback);
    }

    /**
     * مسح cache الأقسام
     */
    public static function invalidateCategories(int $tenantId): void
    {
        Cache::forget(self::tenantKey($tenantId, 'categories'));
        Cache::forget(self::tenantKey($tenantId, 'categories_tree'));
    }

    /**
     * الحصول على إحصائيات الداشبورد مع Cache
     */
    public static function getDashboardStats(int $tenantId, callable $callback): mixed
    {
        $cacheKey = self::tenantKey($tenantId, 'dashboard_stats');
        return Cache::remember($cacheKey, self::STATS_TTL, $callback);
    }

    /**
     * مسح إحصائيات الداشبورد
     */
    public static function invalidateDashboardStats(int $tenantId): void
    {
        Cache::forget(self::tenantKey($tenantId, 'dashboard_stats'));
    }

    /**
     * الحصول على المنتجات المميزة
     */
    public static function getFeaturedProducts(int $tenantId, callable $callback): mixed
    {
        $cacheKey = self::tenantKey($tenantId, 'featured_products');
        return Cache::remember($cacheKey, self::PRODUCTS_TTL, $callback);
    }

    /**
     * مسح كل cache خاص بـ tenant معين
     */
    public static function invalidateAll(int $tenantId): void
    {
        $keys = [
            'settings', 'categories', 'categories_tree',
            'dashboard_stats', 'featured_products', 'products_count'
        ];

        foreach ($keys as $key) {
            Cache::forget(self::tenantKey($tenantId, $key));
        }

        Log::info("[Cache] All cache invalidated for tenant #{$tenantId}");
    }

    /**
     * تخزين بيانات مؤقتة بمفتاح مخصص
     */
    public static function remember(string $key, callable $callback, int $ttl = self::DEFAULT_TTL): mixed
    {
        return Cache::remember($key, $ttl, $callback);
    }

    /**
     * حذف مفتاح محدد
     */
    public static function forget(string $key): void
    {
        Cache::forget($key);
    }

    /**
     * الحصول على عدد المنتجات مع Cache
     */
    public static function getProductsCount(int $tenantId, callable $callback): mixed
    {
        $cacheKey = self::tenantKey($tenantId, 'products_count');
        return Cache::remember($cacheKey, self::PRODUCTS_TTL, $callback);
    }
}
