<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class ProductRecommendation extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'product_id',
        'recommended_id',
        'type', // 'upsell' or 'cross-sell'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function recommendedProduct()
    {
        return $this->belongsTo(Product::class, 'recommended_id');
    }
}
