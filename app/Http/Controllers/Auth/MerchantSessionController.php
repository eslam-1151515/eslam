<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Handles authentication for MERCHANTS and STAFF only.
 * URL: http://{tenant}.fastorder.test:8000/admin/login
 */
class MerchantSessionController extends Controller
{
    /**
     * Show the merchant login form.
     */
    public function create(Request $request): RedirectResponse
    {
        $appUrl  = config('app.url');
        $baseHost = parse_url($appUrl, PHP_URL_HOST) ?: 'fastorder.localhost';
        $scheme   = parse_url($appUrl, PHP_URL_SCHEME) ?: $request->getScheme();
        $port     = parse_url($appUrl, PHP_URL_PORT);
        $portStr  = $port ? ':' . $port : '';

        return redirect()->away("{$scheme}://app.{$baseHost}{$portStr}/login");
    }

    /**
     * Handle merchant login.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        if ($user) {
            $appUrl  = config('app.url');
            $baseHost = parse_url($appUrl, PHP_URL_HOST) ?: 'fastorder.localhost';
            $scheme   = $request->getScheme();
            $port     = $request->getPort();
            $portStr  = ($port && $port != 80 && $port != 443) ? ':' . $port : '';

            if ($user->isMerchant() || $user->isStaff()) {
                $tenant = $user->currentTenant
                    ?? $user->ownedTenants()->first()
                    ?? $user->tenants()->first();

                if ($tenant) {
                    // إذا كان المستخدم على نفس الـ subdomain الصح
                    if ($request->getHost() === "{$tenant->slug}.{$baseHost}") {
                        return redirect('/admin/dashboard');
                    }
                    // وإلا نحوله للـ subdomain الصح
                    return redirect("{$scheme}://{$tenant->slug}.{$baseHost}{$portStr}/admin/dashboard");
                }
            }

            // ليس تاجر أو staff — نسجل خروجه
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/admin/login')->withErrors([
                'email' => 'هذا الحساب غير مصرح له بالدخول لهذه اللوحة.',
            ]);
        }

        return redirect('/admin/login');
    }

    /**
     * Destroy the merchant session → redirect to merchant login.
     */
    public function destroy(Request $request)
    {
        $host    = $request->getHost();
        $scheme  = $request->getScheme();
        $port    = $request->getPort();
        $portStr = ($port && $port != 80 && $port != 443) ? ':' . $port : '';

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // دايماً يرجع لـ admin/login على نفس الـ subdomain
        $loginUrl = "{$scheme}://{$host}{$portStr}/admin/login";

        if ($request->header('X-Inertia')) {
            return \Inertia\Inertia::location($loginUrl);
        }

        return redirect($loginUrl);
    }
}
