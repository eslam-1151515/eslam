<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Check for 'lang' parameter in request (e.g. ?lang=en)
        if ($request->has('lang')) {
            $lang = $request->query('lang');
            if (in_array($lang, ['ar', 'en'])) {
                Session::put('locale', $lang);
            }
        }

        // 2. Resolve language from session or default config
        $locale = Session::get('locale', config('app.locale', 'ar'));
        
        App::setLocale($locale);

        return $next($request);
    }
}
