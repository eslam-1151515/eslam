<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    protected $fillable = [
        'tenant_id',
        'amount',
        'type',
        'description',
        'created_by',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
