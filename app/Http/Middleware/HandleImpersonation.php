<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class HandleImpersonation
{
    /**
     * Authenticate via a subdomain-specific impersonation cookie
     * WITHOUT touching the shared session — so the super admin
     * tab stays fully logged in on app.ordersaif.localhost.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->cookie('impersonate_token');

        if ($token) {
            $data = Cache::get('impersonate_token_' . $token);

            if ($data && !empty($data['user_id'])) {
                // onceUsingId authenticates for this request ONLY —
                // it does NOT write to the session.
                Auth::onceUsingId($data['user_id']);
            }
        }

        return $next($request);
    }
}
