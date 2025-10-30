<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifiedOrganization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Check if user is an organization
        if (!$user || !$user->isOrganization()) {
            abort(403, 'Only organizations can access this resource.');
        }

        // Check if organization is verified
        if (!$user->organization || !$user->organization->is_verified) {
            return redirect()->route('organization.dashboard')
                ->with('error', 'Your organization must be verified before you can create events. Please wait for admin approval.');
        }

        return $next($request);
    }
}