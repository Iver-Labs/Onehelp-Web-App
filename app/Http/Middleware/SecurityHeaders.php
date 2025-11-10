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
        
        // Aggressively remove X-Powered-By header to prevent server version disclosure
        // Using both methods to ensure header is removed
        $response->headers->remove('X-Powered-By');
        if (function_exists('header_remove')) {
            @header_remove('X-Powered-By');
        }
        
        // Add Strict-Transport-Security for HTTPS connections
        if ($request->secure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }
        
        // Comprehensive Content Security Policy with all required directives
        // Note: 'unsafe-inline' is kept for compatibility with existing inline scripts
        // In production, consider refactoring to use nonces or moving scripts to external files
        // All CSP Level 3 directives are defined to prevent "missing directive" alerts
        $csp = "default-src 'self'; " .
            "script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; " .
            "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://fonts.googleapis.com; " .
            "font-src 'self' https://cdnjs.cloudflare.com https://fonts.gstatic.com; " .
            "img-src 'self' data: https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; " .
            "connect-src 'self'; " .
            "media-src 'self'; " .
            "worker-src 'self'; " .
            "child-src 'self'; " .
            "frame-ancestors 'self'; " .
            "form-action 'self'; " .
            "base-uri 'self'; " .
            "object-src 'none'; " .
            "manifest-src 'self';";
        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}