<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\BelongsToTenant;

class AbandonedCart extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'email',
        'phone',
        'session_id',
        'cart_data',
        'recovery_token',
        'recovery_email_sent_at',
        'recovered_at',
    ];

    protected $casts = [
        'cart_data' => 'array',
        'recovery_email_sent_at' => 'datetime',
        'recovered_at' => 'datetime',
    ];

    protected $attributes = [
        'cart_data' => '[]',
    ];

    /**
     * Get the user that owns the abandoned cart.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
