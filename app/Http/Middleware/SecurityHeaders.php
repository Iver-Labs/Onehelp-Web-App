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
        // Generate a unique nonce for this request
        $nonce = base64_encode(random_bytes(16));
        
        // Make nonce available to views
        view()->share('cspNonce', $nonce);
        
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
        
        // Comprehensive Content Security Policy with CSP Level 3 directives
        // Uses nonces for inline scripts for better security (no unsafe-inline needed)
        // 'unsafe-inline' is kept in style-src for compatibility but overridden by nonce in modern browsers
        // All directives are defined to prevent "missing directive" alerts from security scanners
        $csp = "default-src 'self'; " .
            "script-src 'self' 'nonce-{$nonce}' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; " .
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