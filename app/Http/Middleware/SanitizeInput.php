<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SanitizeInput
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Sanitize all input to prevent XSS attacks
        $input = $this->sanitize($request->all());
        $request->merge($input);

        return $next($request);
    }

    /**
     * Recursively sanitize input data
     *
     * @param mixed $data
     * @return mixed
     */
    protected function sanitize($data)
    {
        if (is_array($data)) {
            return array_map([$this, 'sanitize'], $data);
        }

        if (is_string($data)) {
            // Remove null bytes
            $data = str_replace("\0", '', $data);
            
            // Strip tags except for allowed HTML tags in specific fields
            // For most fields, we strip all HTML
            $data = strip_tags($data);
            
            // Trim whitespace
            $data = trim($data);
        }

        return $data;
    }
}
