<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\BelongsToTenant;

class StoreRating extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'order_id',
        'rating_products',
        'rating_shipping',
        'rating_service',
        'comment',
        'is_visible',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
        'rating_products' => 'integer',
        'rating_shipping' => 'integer',
        'rating_service' => 'integer',
    ];

    /**
     * Get the user who left the rating.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order associated with this rating.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
