<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(Request $request): View
    {
        $requestHost = $request->getHost(); // e.g. app.ordersaif.test

        // Super admin يكون دايماً على app.ordersaif.test
        // نشوف من config لو صح، ولو لأ نعتمد على المقارنة المباشرة
        $appUrl = config('app.url');
        $appHost = parse_url($appUrl, PHP_URL_HOST) ?: 'app.ordersaif.test';

        // double check: إما config يطابق أو الـ host يبدأ بـ app.
        $isSuperAdmin = ($requestHost === $appHost)
                     || ($requestHost === 'app.ordersaif.test')
                     || (preg_match('/^app\./i', $requestHost) && !str_contains($requestHost, 'admin'));

        if ($isSuperAdmin) {
            return view('auth.login-superadmin');
        }

        return view('auth.login-merchant');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();
        if ($user) {
            $appUrl = config('app.url');
            $baseHost = parse_url($appUrl, PHP_URL_HOST) ?: 'ordersaif.test';
            $scheme = $request->getScheme();
            $port = $request->getPort();
            $portStr = ($port && $port != 80 && $port != 443) ? ':' . $port : '';

            if ($user->isSuperAdmin()) {
                $superadminUrl = "{$scheme}://app.{$baseHost}{$portStr}/dashboard";
                
                $intended = session()->get('url.intended');
                if ($intended && !str_contains($intended, 'app.' . $baseHost)) {
                    session()->forget('url.intended');
                }
                
                return redirect()->intended($superadminUrl);
            }

            if ($user->isMerchant() || $user->isStaff()) {
                $tenant = $user->currentTenant ?? $user->ownedTenants()->first() ?? $user->tenants()->first();
                if ($tenant) {
                    $merchantUrl = "{$scheme}://{$tenant->slug}.{$baseHost}{$portStr}/admin/dashboard";
                    
                    return redirect($merchantUrl);
                }
            }

            if ($user->isCustomer()) {
                $tenant = $user->currentTenant;
                if ($tenant) {
                    $customerUrl = "{$scheme}://{$tenant->slug}.{$baseHost}{$portStr}/account";
                    
                    $intended = session()->get('url.intended');
                    if ($intended && (str_contains($intended, 'app.' . $baseHost) || !str_contains($intended, $tenant->slug))) {
                        session()->forget('url.intended');
                    }
                    
                    return redirect()->intended($customerUrl);
                }
            }
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // حدد الـ login الصح بناءً على الـ domain الحالي
        $host = $request->getHost();
        $appHost = parse_url(config('app.url'), PHP_URL_HOST) ?: 'ordersaif.localhost';

        $scheme = $request->getScheme();
        $port   = $request->getPort();
        $portStr = ($port && $port != 80 && $port != 443) ? ':' . $port : '';

        $url = $host === $appHost ? route('login') : "{$scheme}://{$host}{$portStr}/admin/login";

        if ($request->header('X-Inertia')) {
            return \Inertia\Inertia::location($url);
        }

        return redirect($url);
    }
}
