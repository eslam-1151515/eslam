<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SanitizeInputs
{
    /**
     * الحقول التي لا يجب تنظيفها (مثل كلمات المرور والـ HTML المقصود)
     */
    protected array $except = [
        'password',
        'password_confirmation',
        'content', // للصفحات التي تحتوي على HTML editor
        'description',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $input = $request->all();
        $input = $this->sanitize($input);
        $request->merge($input);
        return $next($request);
    }

    protected function sanitize(array $data): array
    {
        foreach ($data as $key => $value) {
            if (in_array($key, $this->except)) {
                continue;
            }
            if (is_array($value)) {
                $data[$key] = $this->sanitize($value);
            } elseif (is_string($value)) {
                $data[$key] = strip_tags($value);
            }
        }
        return $data;
    }
}
