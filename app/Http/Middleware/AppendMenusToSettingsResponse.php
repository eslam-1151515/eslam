<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Menu;

class AppendMenusToSettingsResponse
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Check if the request path contains public-api/settings
        if (str_contains($request->getPathInfo(), 'public-api/settings')) {
            // Check if response is a JsonResponse
            if ($response instanceof \Illuminate\Http\JsonResponse) {
                $data = $response->getData(true);
                if (isset($data['data'])) {
                    // Fetch active menus. Scoped automatically by BelongsToTenant.
                    $activeMenus = Menu::active()->get()->groupBy('location')->map(function ($items) {
                        return $items->first()->items ?? [];
                    })->toArray();

                    $data['data']['menus'] = $activeMenus;
                    $response->setData($data);
                }
            }
        }

        return $response;
    }
}
