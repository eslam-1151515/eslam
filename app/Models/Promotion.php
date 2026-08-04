<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class Promotion extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'name',
        'type', // flash_sale, seasonal, clearance, bundle
        'discount_type', // percentage, fixed
        'discount_value',
        'starts_at',
        'ends_at',
        'banner_image',
        'is_active',
        'products',
        'categories',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'discount_value' => 'integer',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'products' => 'array',
        'categories' => 'array',
    ];

    /**
     * Scope للعروض النشطة والحالية
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            });
    }

    public function scopeFlashSale($query)
    {
        return $query->where('type', 'flash_sale');
    }

    public function scopeSeasonal($query)
    {
        return $query->where('type', 'seasonal');
    }

    public function scopeClearance($query)
    {
        return $query->where('type', 'clearance');
    }

    public function scopeBundle($query)
    {
        return $query->where('type', 'bundle');
    }

    /**
     * التحقق مما إذا كان العرض صالحاً وسارياً الآن
     */
    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();
        if ($this->starts_at && $now->lt($this->starts_at)) {
            return false;
        }
        if ($this->ends_at && $now->gt($this->ends_at)) {
            return false;
        }

        return true;
    }

    /**
     * التحقق مما إذا كان العرض منتهياً
     */
    public function isExpired(): bool
    {
        return $this->ends_at && now()->gt($this->ends_at);
    }

    /**
     * التحقق مما إذا كان العرض يشمل منتج معين
     */
    public function appliesToProduct($product): bool
    {
        if (!$this->isValid()) {
            return false;
        }

        $products = $this->products ?: [];
        $categories = $this->categories ?: [];

        if (empty($products) && empty($categories)) {
            return true;
        }

        if (!empty($products) && in_array($product->id, $products)) {
            return true;
        }

        if (!empty($categories) && in_array($product->category_id, $categories)) {
            return true;
        }

        return false;
    }

    /**
     * حساب سعر المنتج بعد الخصم
     */
    public function calculateDiscountedPrice($price): float
    {
        $price = (float) $price;
        if (!$this->isValid()) {
            return $price;
        }

        if ($this->discount_type === 'percentage') {
            $discountAmount = $price * ($this->discount_value / 100);
            $discountedPrice = $price - $discountAmount;
        } else {
            $discountedPrice = $price - $this->discount_value;
        }

        return max(0, round($discountedPrice, 2));
    }

    /**
     * حساب قيمة الخصم فقط
     */
    public function calculateDiscountAmount($price): float
    {
        $price = (float) $price;
        if (!$this->isValid()) {
            return 0;
        }

        if ($this->discount_type === 'percentage') {
            $discountAmount = $price * ($this->discount_value / 100);
        } else {
            $discountAmount = min((float) $this->discount_value, $price);
        }

        return round($discountAmount, 2);
    }

    /**
     * استعلام المنتجات المشمولة في العرض
     */
    public function includedProductsQuery()
    {
        $query = Product::query();

        $products = $this->products ?: [];
        $categories = $this->categories ?: [];

        if (!empty($products) && !empty($categories)) {
            $query->where(function ($q) use ($products, $categories) {
                $q->whereIn('id', $products)
                  ->orWhereIn('category_id', $categories);
            });
        } elseif (!empty($products)) {
            $query->whereIn('id', $products);
        } elseif (!empty($categories)) {
            $query->whereIn('category_id', $categories);
        }

        return $query;
    }

    /**
     * جلب المنتجات المشمولة في العرض
     */
    public function getIncludedProductsAttribute()
    {
        return $this->includedProductsQuery()->get();
    }

    /**
     * الثواني المتبقية لانتهاء العرض
     */
    public function getRemainingSecondsAttribute(): int
    {
        if (!$this->isValid() || !$this->ends_at) {
            return 0;
        }

        return max(0, (int) now()->diffInSeconds($this->ends_at, false));
    }

    /**
     * نص شارة الخصم (Badge)
     */
    public function getDiscountBadgeTextAttribute(): string
    {
        if ($this->discount_type === 'percentage') {
            return '-' . floatval($this->discount_value) . '%';
        }
        return '-' . floatval($this->discount_value) . ' ج.م';
    }

    /**
     * الاسم العربي لنوع العرض
     */
    public function getTypeNameArabicAttribute(): string
    {
        return match ($this->type) {
            'flash_sale' => 'عروض فلاش (محدودة الوقت)',
            'seasonal' => 'عروض موسمية',
            'clearance' => 'تصفية ومخفّضات',
            'bundle' => 'عروض الحزم والباقات',
            default => 'عرض خاص',
        };
    }
}
