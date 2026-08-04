<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class StockMovement extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'product_id',
        'quantity',
        'type', // in, out, adjustment, return
        'description',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
