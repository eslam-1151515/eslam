<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ApiKey extends Model
{
    protected $fillable = ['tenant_id', 'name', 'key', 'last_used_at', 'revoked_at'];

    protected $casts = [
        'last_used_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public static function generate(int $tenantId, string $name): self
    {
        return static::create([
            'tenant_id' => $tenantId,
            'name' => $name,
            'key' => 'fsk_' . Str::random(60), // OrderSaif API Key
        ]);
    }

    public function isActive(): bool
    {
        return is_null($this->revoked_at);
    }

    public function revoke(): void
    {
        $this->update(['revoked_at' => now()]);
    }
}
