<?php

namespace App\Http\Middleware;

use App\Models\ApiKey;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $bearerToken = $request->bearerToken();

        if (!$bearerToken) {
            return response()->json([
                'success' => false,
                'message' => 'API key is required. Please provide Authorization: Bearer <api_key> header.',
            ], 401);
        }

        $apiKey = ApiKey::where('key', $bearerToken)
            ->whereNull('revoked_at')
            ->with('tenant')
            ->first();

        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or revoked API key.',
            ], 401);
        }

        // تحديث وقت آخر استخدام
        $apiKey->update(['last_used_at' => now()]);

        // إرفاق الـ tenant بالـ request
        $request->attributes->set('tenant', $apiKey->tenant);
        $request->attributes->set('api_key', $apiKey);

        return $next($request);
    }
}
