<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\BelongsToTenant;

class Order extends Model
{
    use HasFactory, BelongsToTenant;
    
    protected $fillable = [
        'tenant_id',
        'user_id',
        'reference_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'governorate',
        'payment_method',
        'shipping_cost',
        'coupon_code',
        'discount',
        'items',
        'subtotal',
        'total',
        'status',
        'notes'
    ];

    protected $casts = [
        'items' => 'array',
        'shipping_cost' => 'integer',
        'discount' => 'float',
        'subtotal' => 'integer',
        'total' => 'integer',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * تحديد التوقيت للنموذج
     */
    protected $dateFormat = 'Y-m-d H:i:s';
    
    /**
     * Get the attributes that should be cast to dates.
     */
    protected function casts(): array
    {
        return [
            'items' => 'array',
            'shipping_cost' => 'integer',
            'discount' => 'float',
            'subtotal' => 'integer',
            'total' => 'integer',
            'created_at' => 'datetime:Y-m-d H:i:s',
            'updated_at' => 'datetime:Y-m-d H:i:s',
        ];
    }

    /**
     * توليد رقم مرجعي عشوائي فريد مكون من 5 أرقام
     */
    public static function generateReferenceNumber()
    {
        do {
            $referenceNumber = str_pad(rand(10000, 99999), 5, '0', STR_PAD_LEFT);
        } while (self::where('reference_number', $referenceNumber)->exists());
        
        return $referenceNumber;
    }

    /**
     * إنشاء طلب جديد مع رقم مرجعي
     */
    public static function createWithReference(array $data)
    {
        $data['reference_number'] = self::generateReferenceNumber();
        return self::create($data);
    }

    protected static function booted()
    {
        static::saved(function ($order) {
            if ($order->tenant_id) {
                \App\Services\CacheService::invalidateDashboardStats($order->tenant_id);
            }
            \Illuminate\Support\Facades\Cache::forget('admin_dashboard_stats');
        });

        static::deleted(function ($order) {
            if ($order->tenant_id) {
                \App\Services\CacheService::invalidateDashboardStats($order->tenant_id);
            }
            \Illuminate\Support\Facades\Cache::forget('admin_dashboard_stats');
        });
    }

    /**
     * حساب إجمالي عدد القطع في الطلب
     */
    public function getTotalItemsAttribute()
    {
        return collect($this->items)->sum('quantity');
    }
}
