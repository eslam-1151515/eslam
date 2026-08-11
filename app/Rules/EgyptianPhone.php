<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\User;
use App\Models\Tenant;

class EgyptianPhone implements ValidationRule
{
    protected ?int $exceptUserId;
    protected ?int $exceptTenantId;
    protected bool $required;

    public function __construct(?int $exceptUserId = null, ?int $exceptTenantId = null, bool $required = true)
    {
        $this->exceptUserId = $exceptUserId;
        $this->exceptTenantId = $exceptTenantId;
        $this->required = $required;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $phone = trim((string) $value);

        if (empty($phone)) {
            if ($this->required) {
                $fail('يرجى كتابة رقم الهاتف أولاً.');
            }
            return;
        }

        $clean = preg_replace('/[^0-9\+]/', '', $phone);

        // Valid Egyptian mobile format (+2010..., 2010..., 010..., 011..., 012..., 015...)
        if (!preg_match('/^(?:\+?20|0)?1[0125][0-9]{8}$/', $clean)) {
            $fail('يرجى إدخال رقم هاتف مصري صحيح (مثال: 01012345678 أو 01112345678).');
            return;
        }

        // Normalize to standard 01XXXXXXXXX
        $digitsOnly = preg_replace('/[^0-9]/', '', $clean);
        if (str_starts_with($digitsOnly, '20') && strlen($digitsOnly) === 12) {
            $normalized = '0' . substr($digitsOnly, 2);
        } else {
            $normalized = $digitsOnly;
        }

        $variations = [
            $normalized,
            '20' . substr($normalized, 1),
            '+20' . substr($normalized, 1),
        ];

        // System-wide uniqueness check in users table
        $userQuery = User::whereIn('phone', $variations);
        if ($this->exceptUserId) {
            $userQuery->where('id', '!=', $this->exceptUserId);
        }
        if ($userQuery->exists()) {
            $fail('رقم الهاتف هذا مستخدم بالفعل ومسجل في حساب آخر بالنظام. يرجى استخدام رقم هاتف جديد.');
            return;
        }

        // System-wide uniqueness check in tenants table
        $tenantQuery = Tenant::whereIn('phone', $variations);
        if ($this->exceptTenantId) {
            $tenantQuery->where('id', '!=', $this->exceptTenantId);
        }
        if ($tenantQuery->exists()) {
            $fail('رقم الهاتف هذا مستخدم بالفعل ومسجل في حساب آخر بالنظام. يرجى استخدام رقم هاتف جديد.');
            return;
        }
    }
}
