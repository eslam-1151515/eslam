<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'tenant_id',
        'name',
        'email',
        'phone',
        'password',
        'user_type',
        'avatar',
        'last_login_at',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    /**
     * Get the tenants associated with the user through the pivot table.
     */
    public function tenants(): BelongsToMany
    {
        return $this->belongsToMany(Tenant::class, 'tenant_users')
                    ->withPivot('role', 'permissions')
                    ->withTimestamps();
    }

    /**
     * Get the tenants owned by the user directly.
     */
    public function ownedTenants(): HasMany
    {
        return $this->hasMany(Tenant::class, 'owner_id');
    }

    /**
     * Get the specific tenant the user belongs to (e.g. for staff/customers).
     */
    public function currentTenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    /**
     * Get the roles for the user, optionally filtered by tenant.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles')
                    ->withPivot('tenant_id')
                    ->withTimestamps();
    }

    /**
     * Check if the user has a specific permission for a tenant.
     */
    public function hasPermission(string $permissionSlug, $tenantId = null): bool
    {
        // Super admins have all permissions
        if ($this->isSuperAdmin()) {
            return true;
        }

        if ($tenantId === null) {
            $tenantId = session()->get('tenant_id') ?? config('tenant.id');
        }

        // First, check the roles/permissions from user_roles and roles tables
        $hasRbacPermission = $this->roles()
            ->where(function ($query) use ($tenantId) {
                $query->where('user_roles.tenant_id', $tenantId)
                      ->orWhereNull('user_roles.tenant_id'); // Global roles
            })
            ->whereHas('permissions', function ($query) use ($permissionSlug) {
                $query->where('slug', $permissionSlug);
            })
            ->exists();

        if ($hasRbacPermission) {
            return true;
        }

        // Next, check the permissions in tenant_users table (pivot) for merchant staff
        $tenantUser = \Illuminate\Support\Facades\DB::table('tenant_users')
            ->where('user_id', $this->id)
            ->where('tenant_id', $tenantId)
            ->first();

        if ($tenantUser) {
            // Manager role has all permissions by default
            if ($tenantUser->role === 'manager') {
                return true;
            }

            // Check if the permission is explicitly allowed in the JSON array
            $permissions = json_decode($tenantUser->permissions ?? '[]', true) ?? [];
            if (in_array($permissionSlug, $permissions)) {
                return true;
            }
        }

        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | User Type Convenience Helpers
    |--------------------------------------------------------------------------
    */

    public function isSuperAdmin(): bool
    {
        return $this->user_type === 'super_admin';
    }

    public function isMerchant(): bool
    {
        return $this->user_type === 'merchant';
    }

    public function isCustomer(): bool
    {
        return $this->user_type === 'customer';
    }

    public function isStaff(): bool
    {
        return $this->user_type === 'staff';
    }
}
