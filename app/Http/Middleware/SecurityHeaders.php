<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Add security headers to prevent common vulnerabilities
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');
        
        // Remove X-Powered-By header to prevent server version disclosure
        $response->headers->remove('X-Powered-By');
        
        // Enhanced Content Security Policy
        // Removed unsafe-eval for better security
        // Kept unsafe-inline for now to avoid breaking existing inline scripts/styles
        // Added missing directives: frame-ancestors, form-action, base-uri, object-src
        // Restricted img-src to specific trusted sources instead of wildcard
        $csp = "default-src 'self'; " .
            "script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; " .
            "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://fonts.googleapis.com; " .
            "font-src 'self' https://cdnjs.cloudflare.com https://fonts.gstatic.com; " .
            "img-src 'self' data: https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; " .
            "connect-src 'self'; " .
            "frame-ancestors 'self'; " .
            "form-action 'self'; " .
            "base-uri 'self'; " .
            "object-src 'none';";
        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}