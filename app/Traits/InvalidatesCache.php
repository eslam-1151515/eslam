<?php
namespace App\Traits;

use App\Services\CacheService;

trait InvalidatesCache
{
    /**
     * يُستدعى تلقائياً بعد الحفظ
     */
    protected static function bootInvalidatesCache(): void
    {
        static::saved(function ($model) {
            if (isset($model->tenant_id)) {
                CacheService::invalidateAll($model->tenant_id);
            }
        });

        static::deleted(function ($model) {
            if (isset($model->tenant_id)) {
                CacheService::invalidateAll($model->tenant_id);
            }
        });
    }
}
