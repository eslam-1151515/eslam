<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OptimizeStorefrontResponse
{
    /**
     * Handle an incoming request.
     * Optimize storefront response with caching headers and safe HTML minification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Add standard performance headers
        $response->headers->set('X-DNS-Prefetch-Control', 'on');
        $response->headers->set('Vary', 'Accept-Encoding');
        $response->headers->set('X-Storefront-Optimized', '1');

        // Apply caching headers for storefront GET requests
        if ($this->shouldCacheResponse($request, $response)) {
            $response->headers->set('Cache-Control', 'public, max-age=60, s-maxage=300, stale-while-revalidate=600');
        }

        // Apply HTML minification if response is HTML
        if ($this->isHtmlResponse($response) && $this->shouldMinifyHtml()) {
            $this->minifyHtml($response);
        }

        return $response;
    }

    /**
     * Determine if the response should receive public cache headers.
     */
    protected function shouldCacheResponse(Request $request, Response $response): bool
    {
        if (!$request->isMethod('GET') || $response->getStatusCode() !== 200) {
            return false;
        }

        // Avoid caching sensitive or user-specific pages (cart, checkout, account, orders)
        $excludedPaths = ['*/cart*', '*/checkout*', '*/account*', '*/orders*', '*/login*', '*/register*', '*/api/*'];
        foreach ($excludedPaths as $pattern) {
            if ($request->is($pattern)) {
                return false;
            }
        }

        // Do not override explicit anti-caching or private cache control if already set by controller
        $currentCacheControl = $response->headers->get('Cache-Control', '');
        if (str_contains($currentCacheControl, 'private') || str_contains($currentCacheControl, 'no-store') || str_contains($currentCacheControl, 'no-cache')) {
            return false;
        }

        return true;
    }

    /**
     * Determine if the response content type is HTML.
     */
    protected function isHtmlResponse(Response $response): bool
    {
        $contentType = $response->headers->get('Content-Type', '');
        if (empty($contentType) && method_exists($response, 'original') && is_string($response->getContent())) {
            return true;
        }

        return str_contains($contentType, 'text/html');
    }

    /**
     * Determine if HTML minification should be enabled.
     */
    protected function shouldMinifyHtml(): bool
    {
        // Check custom config/env first, fallback to minifying when not in active debug mode or always for storefront
        return config('storefront.minify_html', !config('app.debug', false));
    }

    /**
     * Safely minify HTML content by removing comments and excessive whitespace.
     */
    protected function minifyHtml(Response $response): void
    {
        $content = $response->getContent();
        if (!is_string($content) || empty($content)) {
            return;
        }

        // 1. Remove HTML comments (excluding IE conditional comments <!--[if ... and <!--# ...)
        $content = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>|#|!))[\s\S]*?-->/', '', $content);

        // 2. Collapse whitespace around tags safely without breaking <pre>, <textarea>, or <script>
        // Split by pre/textarea/script blocks to protect them
        $parts = preg_split('/(<(?:pre|textarea|script)[^>]*>[\s\S]*?<\/(?:pre|textarea|script)>)/i', $content, -1, PREG_SPLIT_DELIM_CAPTURE);
        
        if (is_array($parts)) {
            foreach ($parts as $index => &$part) {
                // Only minify non-protected blocks (even indices in split)
                if ($index % 2 === 0) {
                    // Replace multiple spaces/tabs/newlines with a single space
                    $part = preg_replace('/\s+/', ' ', $part);
                    // Remove whitespace between tags where safe
                    $part = preg_replace('/>\s+</', '><', $part);
                    $part = trim($part);
                }
            }
            $content = implode('', $parts);
        }

        $response->setContent($content);

        // Update Content-Length header if present
        if ($response->headers->has('Content-Length')) {
            $response->headers->set('Content-Length', (string) strlen($content));
        }
    }
}
