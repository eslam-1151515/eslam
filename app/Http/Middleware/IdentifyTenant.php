<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $appUrl = config('app.url');
        $baseHost = parse_url($appUrl, PHP_URL_HOST);

        $tenant = null;
        $subdomain = null;

        // 1. Try to match by custom domain first
        $tenant = Tenant::where('custom_domain', $host)->first();

        if (!$tenant) {
            // 2. Extract subdomain if request host ends with base host
            if ($baseHost && str_ends_with($host, '.' . $baseHost)) {
                $subdomain = substr($host, 0, -strlen('.' . $baseHost));
            } else {
                $parts = explode('.', $host);
                if (count($parts) > 1 && !filter_var($host, FILTER_VALIDATE_IP)) {
                    $subdomain = $parts[0];
                }
            }

            // Skip lookup for main domains and common subdomains
            if ($subdomain && !in_array(strtolower($subdomain), ['www', 'app', 'admin'])) {
                $tenant = Tenant::where('slug', $subdomain)->first();
            }
        }

        // 3. If request is on app domain or main domain (or no tenant by subdomain)
        if (!$tenant) {
            // A. If logged in merchant user, resolve directly from user's tenant
            if (auth()->check() && !auth()->user()->isSuperAdmin()) {
                $user = auth()->user();
                $tenant = $user->ownedTenants()->first() ?? $user->currentTenant;
            }

            // B. If super admin impersonating
            if (!$tenant && session()->has('impersonated_tenant_id')) {
                $tenant = Tenant::find(session('impersonated_tenant_id'));
            }

            // C. Session fallback
            if (!$tenant && session()->has('tenant_id')) {
                $tenant = Tenant::find(session('tenant_id'));
            }
        }

        // If a tenant was expected on a subdomain but not found, abort
        if (!$tenant) {
            $isMainDomain = ($host === $baseHost || 
                             in_array(strtolower($subdomain), ['www', 'app', 'admin']) || 
                             $host === 'localhost' || 
                             $host === '127.0.0.1');

            if (!$isMainDomain && str_contains($request->getPathInfo(), '/shop')) {
                abort(404, 'Store not found');
            }
        }

        if ($tenant) {
            // Bind the tenant to the service container
            app()->instance(Tenant::class, $tenant);

            // Store in config for scopes and traits
            config(['app.tenant' => $tenant]);
            config(['tenant.id' => $tenant->id]);
            config(['tenant.current' => $tenant]);
            
            // Store in session
            session(['tenant_id' => $tenant->id]);
            $request->attributes->set('tenant', $tenant);
        }

        return $next($request);
    }
}
