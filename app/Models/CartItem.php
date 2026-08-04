<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id',
        'product_id',
        'product_variant_id',
        'quantity',
        'price',
        'options',
        'saved_for_later',
    ];

    protected $casts = [
        'options'          => 'array',
        'saved_for_later'  => 'boolean',
        'price'            => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function getTotalAttribute(): int
    {
        return (int) $this->price * $this->quantity;
    }
}
