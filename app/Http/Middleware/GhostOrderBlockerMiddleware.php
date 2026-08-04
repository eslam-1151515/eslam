<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Setting;
use App\Models\BlacklistRecord;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GhostOrderBlockerMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Check if the blocker is enabled in settings
        $blockerEnabled = Setting::get('ghost_blocker_enabled', '0');
        if ($blockerEnabled != '1') {
            return $next($request);
        }

        $tenantId = session()->get('tenant_id') ?? config('tenant.id');

        // Extract customer details from request
        $ip = $request->ip();
        $phone = trim($request->input('customer_phone', ''));
        $email = trim(strtolower($request->input('customer_email', '')));
        
        // 2. Check Database Blacklist Records
        if ($tenantId) {
            // Check IP
            $isIpBlocked = BlacklistRecord::where('tenant_id', $tenantId)
                ->where('type', 'ip')
                ->where('value', $ip)
                ->exists();

            if ($isIpBlocked) {
                return $this->blockResponse($request);
            }

            // Check Phone
            if (!empty($phone)) {
                // Normalize phone (remove spaces, dashes)
                $normalizedPhone = preg_replace('/[^0-9]/', '', $phone);
                
                $isPhoneBlocked = BlacklistRecord::where('tenant_id', $tenantId)
                    ->where('type', 'phone')
                    ->where(function($q) use ($phone, $normalizedPhone) {
                        $q->where('value', $phone)
                          ->orWhere('value', $normalizedPhone);
                    })
                    ->exists();

                if ($isPhoneBlocked) {
                    return $this->blockResponse($request);
                }
            }

            // Check Email
            if (!empty($email)) {
                $isEmailBlocked = BlacklistRecord::where('tenant_id', $tenantId)
                    ->where('type', 'email')
                    ->where('value', $email)
                    ->exists();

                if ($isEmailBlocked) {
                    return $this->blockResponse($request);
                }
            }
        }

        // 3. Check Settings-defined blocked email domains or addresses
        $blockedEmailsSetting = Setting::get('blocked_emails', '');
        if (!empty($blockedEmailsSetting) && !empty($email)) {
            $blockedPatterns = array_map('trim', explode(',', strtolower($blockedEmailsSetting)));
            foreach ($blockedPatterns as $pattern) {
                if (empty($pattern)) continue;

                // Simple wildcard conversion (*@spam.com to regex)
                $regexPattern = str_replace(['.', '*'], ['\.', '.*'], $pattern);
                if (preg_match('/^' . $regexPattern . '$/i', $email) || str_contains($email, $pattern)) {
                    return $this->blockResponse($request);
                }
            }
        }

        // 4. Phone number sanity & validation checks
        if (!empty($phone)) {
            // Simple check for spam phone patterns (e.g. sequential digits "123456", repeated "000000")
            if (preg_match('/^(\d)\1{5,}$/', $phone) || in_array($phone, ['12345678', '123456789', '1234567890', '0123456789'])) {
                return $this->blockResponse($request, 'الرجاء إدخال رقم هاتف صحيح وصالح لاستقبال مكالمات التأكيد.');
            }

            // Phone verification threshold check
            $phoneVerificationEnabled = Setting::get('phone_verification_enabled', '0') == '1';
            if ($phoneVerificationEnabled) {
                $minOrderForVerification = (float) Setting::get('phone_verification_min_order', 0);
                
                // Calculate order value
                $subtotal = 0;
                $items = $request->input('items', []);
                if (is_array($items)) {
                    foreach ($items as $item) {
                        $price = isset($item['price']) ? (float)$item['price'] : 0;
                        $qty = isset($item['qty']) ? (int)$item['qty'] : 1;
                        $subtotal += ($price * $qty);
                    }
                }

                if ($subtotal >= $minOrderForVerification) {
                    // Check if Egyptian number is valid (e.g. starts with 010, 011, 012, 015 and has 11 digits)
                    // (Assuming typical target market is Egypt, but make it friendly)
                    $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
                    // Standard Egypt phone regex
                    if (strlen($cleanPhone) === 11 && !preg_match('/^(010|011|012|015)\d{8}$/', $cleanPhone)) {
                        return $this->blockResponse($request, 'رقم الهاتف غير متوافق مع صيغة أرقام الهواتف الصحيحة (01xxxxxxxxx).');
                    }
                    if (strlen($cleanPhone) < 8 || strlen($cleanPhone) > 15) {
                        return $this->blockResponse($request, 'الرجاء إدخال رقم هاتف صحيح لضمان تأكيد شحن طلبك.');
                    }
                }
            }
        }

        return $next($request);
    }

    /**
     * Return block response.
     */
    protected function blockResponse(Request $request, ?string $message = null): Response
    {
        $errMsg = $message ?? 'عذراً، لم نتمكن من معالجة طلبك حالياً. يرجى مراجعة بيانات الاتصال أو التواصل مع خدمة العملاء.';

        if ($request->expectsJson() || $request->ajax() || $request->is('api/*')) {
            return response()->json([
                'success' => false,
                'message' => $errMsg,
            ], 403);
        }

        return redirect()->back()
            ->withInput()
            ->withErrors(['customer_phone' => $errMsg]);
    }
}
