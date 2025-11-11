<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return view('pages.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Map 'password' to 'password_hash' for authentication
        $authCredentials = [
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ];

        $remember = $request->boolean('remember');

        if (Auth::attempt($authCredentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Check if account is active
            if (!$user->is_active) {
                Auth::logout();
                \Log::channel('security')->warning('Inactive account login attempt', [
                    'email' => $credentials['email'],
                    'ip' => $request->ip()
                ]);
                return back()->withErrors([
                    'email' => 'Your account has been deactivated. Please contact support.',
                ]);
            }

            // Log successful login
            \Log::channel('security')->info('User logged in successfully', [
                'user_id' => $user->user_id,
                'email' => $user->email,
                'ip' => $request->ip()
            ]);

            // Update last login
            $user->updateLastLogin();

            // Redirect based on user type
            if ($user->isAdmin()) {
                return redirect()->intended('/admin/dashboard');
            } elseif ($user->isOrganization()) {
                return redirect()->intended('/organization/dashboard');
            } else {
                return redirect()->intended('/');
            }
        }

        // Log failed login attempt
        \Log::channel('security')->warning('Failed login attempt', [
            'email' => $credentials['email'],
            'ip' => $request->ip()
        ]);

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'You have been logged out successfully.');
    }
}