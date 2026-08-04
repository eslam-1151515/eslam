<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class BlacklistRecord extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'type', // 'ip', 'phone', 'email'
        'value',
        'reason',
    ];

    /**
     * Scope query to find specific type of record.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
