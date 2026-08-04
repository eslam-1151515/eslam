<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\BelongsToTenant;

class ShippingGovernorate extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'name',
        'price',
        'is_active'
    ];

    protected $casts = [
        'price' => 'integer', // تغيير من decimal إلى integer
        'is_active' => 'boolean'
    ];

    // Scope للمحافظات النشطة فقط
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Get formatted price
    public function getFormattedPriceAttribute()
    {
        return $this->price . ' جنيه'; // إزالة number_format
    }
}
