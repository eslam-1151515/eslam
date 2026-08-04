<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantIsActive
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = app()->bound(Tenant::class) ? app(Tenant::class) : config('app.tenant');

        if ($tenant) {
            $isSuperAdmin = \Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::user()?->user_type === 'super_admin';

            if (!$tenant->is_active && !$isSuperAdmin) {
                // If merchant user is logged in, invalidate session & logout immediately
                if (\Illuminate\Support\Facades\Auth::check()) {
                    \Illuminate\Support\Facades\Auth::guard('web')->logout();
                    if ($request->hasSession()) {
                        $request->session()->invalidate();
                        $request->session()->regenerateToken();
                    }

                    if ($request->expectsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'تم إيقاف المتجر مؤقتاً. تم تسجيل الخروج.',
                        ], 403);
                    }

                    return redirect()->route('merchant.login', ['tenant' => $tenant->slug])
                        ->with('error', 'تم إيقاف هذا المتجر مؤقتاً بواسطة إدارة المنصة.');
                }

                abort(403, 'This store is inactive. Please contact support.');
            }

            $hasTrial = !is_null($tenant->trial_ends_at);
            $hasSubscription = !is_null($tenant->subscription_ends_at);

            if ($hasTrial || $hasSubscription) {
                $hasActiveTrial = $hasTrial && $tenant->trial_ends_at->isFuture();
                $hasActiveSubscription = $hasSubscription && $tenant->subscription_ends_at->isFuture();

                if (!$hasActiveTrial && !$hasActiveSubscription && !$isSuperAdmin) {
                    if (\Illuminate\Support\Facades\Auth::check()) {
                        \Illuminate\Support\Facades\Auth::guard('web')->logout();
                        if ($request->hasSession()) {
                            $request->session()->invalidate();
                            $request->session()->regenerateToken();
                        }
                    }
                    abort(403, 'The subscription or trial period for this store has expired. Please renew or upgrade.');
                }
            }
        }

        return $next($request);
    }
}
