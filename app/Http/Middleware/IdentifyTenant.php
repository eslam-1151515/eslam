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

        // Extract subdomain if request host ends with base host
        if ($baseHost && str_ends_with($host, '.' . $baseHost)) {
            $subdomain = substr($host, 0, -strlen('.' . $baseHost));
        } else {
            $parts = explode('.', $host);
            if (count($parts) > 1 && !filter_var($host, FILTER_VALIDATE_IP)) {
                $subdomain = $parts[0];
            }
        }

        $isSubdomainRequest = $subdomain && !in_array(strtolower($subdomain), ['www', 'app', 'admin']);
        $isCustomDomainRequest = !$isSubdomainRequest && $baseHost && $host !== $baseHost && !in_array(strtolower($host), ['localhost', '127.0.0.1']);

        // 1. Try to match by custom domain first
        $tenant = Tenant::where('custom_domain', $host)->first();

        if (!$tenant && $isSubdomainRequest) {
            // 2. Try to match by subdomain
            $tenant = Tenant::where('slug', $subdomain)->first();
        }

        // 3. If this is a store subdomain or custom domain request but the tenant is deleted or not found
        if (!$tenant && ($isSubdomainRequest || $isCustomDomainRequest)) {
            abort(404, 'المتجر غير موجود أو تم إغلاقه');
        }

        // 4. Main domain fallbacks (for app.domain.com dashboard access)
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

        // 5. If tenant is deactivated
        if ($tenant && !$tenant->is_active && !auth()->check() && !session()->has('impersonated_tenant_id')) {
            abort(403, 'المتجر معطل حالياً');
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
