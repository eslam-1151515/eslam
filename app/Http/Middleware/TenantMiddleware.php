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
            // Check if subdomain tenant is bound
            $subdomain = explode('.', $request->getHost())[0] ?? null;
            if ($subdomain && !in_array(strtolower($subdomain), ['app', 'www', 'admin'])) {
                $tenant = Tenant::where('slug', $subdomain)->first();
            }

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

            if (!$tenant) {
                $tenant = Tenant::where('custom_domain', $request->getHost())->first();
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
