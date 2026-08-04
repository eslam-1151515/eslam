<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\BelongsToTenant;

class Product extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'category_id',
        'name',
        'description',
        'price',
        'price_before',
        'price_after',
        'price_tiers',
        'stock',
        'low_stock_threshold',
        'shipping_type',
        'image_url',
        'main_image_path',
        'sizes',
        'colors',
        'variants_stock',
    ];

    protected $casts = [
        'price' => 'integer',
        'price_before' => 'integer',
        'price_after' => 'integer',
        'price_tiers' => 'array',
        'sizes' => 'array',
        'colors' => 'array',
        'variants_stock' => 'array',
    ];

    protected $appends = ['image_display_url'];

    public static function resolveImageUrl(?string $path): ?string
    {
        if (!$path) return null;

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        $cleanPath = ltrim($path, '/');

        if (str_starts_with($cleanPath, 'images/')) {
            return asset($cleanPath);
        }

        if (str_starts_with($cleanPath, 'storage/')) {
            return asset($cleanPath);
        }

        if (file_exists(public_path($cleanPath))) {
            return asset($cleanPath);
        }

        if (file_exists(public_path('images/' . $cleanPath))) {
            return asset('images/' . $cleanPath);
        }

        if (file_exists(public_path('images/products/' . $cleanPath))) {
            return asset('images/products/' . $cleanPath);
        }

        return asset('storage/' . $cleanPath);
    }

    public function getImageDisplayUrlAttribute()
    {
        $path = $this->main_image_path ?: $this->image_url;
        return static::resolveImageUrl($path);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function upsells()
    {
        return $this->belongsToMany(Product::class, 'product_recommendations', 'product_id', 'recommended_id')
            ->wherePivot('type', 'upsell')
            ->withPivot('type')
            ->withTimestamps();
    }

    protected static function booted()
    {
        static::saved(function ($product) {
            if ($product->tenant_id) {
                \App\Services\CacheService::invalidateDashboardStats($product->tenant_id);
            }
            \Illuminate\Support\Facades\Cache::forget('admin_dashboard_stats');
        });

        static::deleted(function ($product) {
            if ($product->tenant_id) {
                \App\Services\CacheService::invalidateDashboardStats($product->tenant_id);
            }
            \Illuminate\Support\Facades\Cache::forget('admin_dashboard_stats');
        });
    }

    public function crossSells()
    {
        return $this->belongsToMany(Product::class, 'product_recommendations', 'product_id', 'recommended_id')
            ->wherePivot('type', 'cross-sell')
            ->withPivot('type')
            ->withTimestamps();
    }
}
