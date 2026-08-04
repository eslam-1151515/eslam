<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class Coupon extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'code',
        'type',
        'value',
        'min_order_value',
        'max_uses',
        'uses_count',
        'starts_at',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'value' => 'integer',
        'min_order_value' => 'integer',
        'max_uses' => 'integer',
        'uses_count' => 'integer',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Scope للحصول على الكوبونات النشطة
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * التحقق مما إذا كان الكوبون صالحاً للاستخدام
     */
    public function isValidForOrder($orderValue): bool
    {
        if (!$this->is_active) {
            return false;
        }

        // تحقق من التواريخ
        $now = now();
        if ($this->starts_at && $now->lt($this->starts_at)) {
            return false;
        }
        if ($this->expires_at && $now->gt($this->expires_at)) {
            return false;
        }

        // تحقق من الحد الأقصى للاستخدام
        if ($this->max_uses !== null && $this->uses_count >= $this->max_uses) {
            return false;
        }

        // تحقق من الحد الأدنى لقيمة الطلب
        if ($this->min_order_value !== null && $orderValue < $this->min_order_value) {
            return false;
        }

        return true;
    }

    /**
     * احتساب قيمة الخصم بناءً على قيمة الطلب
     */
    public function calculateDiscount($orderValue): int
    {
        if ($this->type === 'percentage') {
            return (int) round($orderValue * ($this->value / 100));
        }

        // ثابت
        return (int) min((float) $this->value, (float) $orderValue);
    }
}
