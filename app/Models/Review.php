<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = [
        'tenant_id', 'product_id', 'user_id', 'order_id',
        'rating', 'title', 'body', 'images',
        'helpful_count', 'is_approved', 'is_verified_purchase',
        'merchant_reply', 'replied_at'
    ];

    protected $casts = [
        'images'               => 'array',
        'is_approved'          => 'boolean',
        'is_verified_purchase' => 'boolean',
        'replied_at'           => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }
}
