<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tenantSlug = $request->route('tenant');

        if ($tenantSlug) {
            $tenant = Tenant::where('slug', $tenantSlug)->first();

            if (!$tenant) {
                // If tenant is not found by slug, fallback to check by custom domain
                $tenant = Tenant::where('custom_domain', $request->getHost())->first();

                if (!$tenant) {
                    abort(404, 'Store not found.');
                }
            }

            // Set the tenant in session and config
            if ($request->hasSession()) {
                session()->put('tenant_id', $tenant->id);
            }
            config(['tenant.id' => $tenant->id]);
            config(['tenant.current' => $tenant]);
            config(['app.tenant' => $tenant]);
            app()->instance(Tenant::class, $tenant);
            \Illuminate\Support\Facades\URL::defaults(['tenant' => $tenant->slug]);
            $request->attributes->set('tenant', $tenant);

            // Forget the tenant parameter so it is not passed to the controller methods
            $request->route()->forgetParameter('tenant');
        } else {
            $host = $request->getHost();
            $appUrl = config('app.url');
            $baseHost = parse_url($appUrl, PHP_URL_HOST);

            $subdomain = null;
            if ($baseHost && str_ends_with($host, '.' . $baseHost)) {
                $subdomain = substr($host, 0, -strlen('.' . $baseHost));
            } else {
                $parts = explode('.', $host);
                if (count($parts) > 1 && !filter_var($host, FILTER_VALIDATE_IP)) {
                    $subdomain = $parts[0];
                }
            }

            $isSubdomain = $subdomain && !in_array(strtolower($subdomain), ['app', 'www', 'admin']);
            $isCustomDomain = !$isSubdomain && $baseHost && $host !== $baseHost && !in_array(strtolower($host), ['localhost', '127.0.0.1']);

            $tenant = null;
            if ($isSubdomain) {
                $tenant = Tenant::where('slug', $subdomain)->first();
            } elseif ($isCustomDomain) {
                $tenant = Tenant::where('custom_domain', $host)->first();
            }

            // If accessing a store subdomain or custom domain, but the tenant does NOT exist in DB (deleted/non-existent)
            if (!$tenant && ($isSubdomain || $isCustomDomain)) {
                abort(404, 'المتجر غير موجود أو تم إغلاقه');
            }

            // Fallbacks for main domain app.domain.com dashboard access
            if (!$tenant && auth()->check() && !auth()->user()->isSuperAdmin()) {
                $user = auth()->user();
                $tenant = $user->ownedTenants()->first() ?? $user->currentTenant;
            }

            if (!$tenant && session()->has('impersonated_tenant_id')) {
                $tenant = Tenant::find(session('impersonated_tenant_id'));
            }

            if (!$tenant && app()->bound(Tenant::class)) {
                $tenant = app(Tenant::class);
            }

            if ($tenant) {
                if ($request->hasSession()) {
                    session()->put('tenant_id', $tenant->id);
                }
                config(['tenant.id' => $tenant->id]);
                config(['tenant.current' => $tenant]);
                config(['app.tenant' => $tenant]);
                app()->instance(Tenant::class, $tenant);
                \Illuminate\Support\Facades\URL::defaults(['tenant' => $tenant->slug]);
                $request->attributes->set('tenant', $tenant);
            }
        }

        return $next($request);
    }
}
