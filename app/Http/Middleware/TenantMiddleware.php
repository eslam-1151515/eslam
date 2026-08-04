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
            // Check by custom domain if tenant slug is not in the URL (e.g. custom domain matching)
            $tenant = Tenant::where('custom_domain', $request->getHost())->first();

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
