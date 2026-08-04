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
                // Fallback for local testing (e.g. merchant1.localhost or merchant1.fastorder.test when baseHost is fastorder.test)
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

        // If a tenant was expected but not found, abort
        if (!$tenant) {
            $isMainDomain = ($host === $baseHost || 
                             in_array(strtolower($subdomain), ['www', 'app', 'admin']) || 
                             $host === 'localhost' || 
                             $host === '127.0.0.1');

            if (!$isMainDomain) {
                abort(404, 'Tenant not found');
            }
        }

        if ($tenant) {
            // Bind the tenant to the service container
            app()->instance(Tenant::class, $tenant);

            // Store in config for scopes and traits
            config(['app.tenant' => $tenant]);
            config(['tenant.id' => $tenant->id]);
            
            // Also store in session if needed
            session(['tenant_id' => $tenant->id]);
            $request->attributes->set('tenant', $tenant);
        }

        return $next($request);
    }
}
