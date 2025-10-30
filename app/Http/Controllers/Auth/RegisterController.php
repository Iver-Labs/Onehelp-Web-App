<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Volunteer;
use App\Models\Organization;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    /**
     * Show the registration form
     */
    public function showRegistrationForm()
    {
        return view('pages.register');
    }

    /**
     * Handle volunteer registration
     */
    public function registerVolunteer(Request $request)
    {
        // Validate volunteer-specific fields
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'date_of_birth' => 'nullable|date|before:today',
            'address' => 'nullable|string',
            'bio' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();

        try {
            // Create user account
            $user = User::create([
                'email' => $validated['email'],
                'password_hash' => Hash::make($validated['password']),
                'user_type' => 'volunteer',
                'is_active' => true,
            ]);

            // Create volunteer profile
            $volunteer = Volunteer::create([
                'user_id' => $user->user_id,
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'date_of_birth' => $validated['date_of_birth'] ?? null,
                'address' => $validated['address'] ?? null,
                'bio' => $validated['bio'] ?? null,
                'total_hours' => 0,
                'events_completed' => 0,
            ]);

            // Create welcome notification
            Notification::create([
                'user_id' => $user->user_id,
                'notification_type' => 'welcome',
                'message' => 'Welcome to OneHelp! Start exploring volunteer opportunities.',
                'is_read' => false,
            ]);

            DB::commit();

            // Fire the registered event (for email verification)
            event(new Registered($user));

            // Log the user in
            auth()->login($user);

            return redirect()->route('home')
                ->with('success', 'Registration successful! Please check your email to verify your account.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'Registration failed. Please try again.']);
        }
    }

    /**
     * Handle organization registration
     */
    public function registerOrganization(Request $request)
    {
        // Validate organization-specific fields
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'org_name' => 'required|string|max:255',
            'org_type' => 'required|string|in:NGO,Non-Profit,Community Group,School,Religious Organization,Other',
            'contact_person' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'description' => 'required|string|max:1000',
            'registration_number' => 'nullable|string|max:100',
            'logo_image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048', // 2MB max
        ]);

        DB::beginTransaction();

        try {
            // Handle logo upload
            $logoPath = null;
            if ($request->hasFile('logo_image')) {
                $logoPath = $request->file('logo_image')->store('logos', 'public');
            }

            // Create user account
            $user = User::create([
                'email' => $validated['email'],
                'password_hash' => Hash::make($validated['password']),
                'user_type' => 'organization',
                'is_active' => true,
            ]);

            // Create organization profile
            $organization = Organization::create([
                'user_id' => $user->user_id,
                'org_name' => $validated['org_name'],
                'org_type' => $validated['org_type'],
                'contact_person' => $validated['contact_person'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'description' => $validated['description'],
                'registration_number' => $validated['registration_number'] ?? null,
                'logo_image' => $logoPath,
                'is_verified' => false, // Must be verified before creating events
            ]);

            // Create welcome notification
            Notification::create([
                'user_id' => $user->user_id,
                'notification_type' => 'verification_pending',
                'message' => 'Welcome to OneHelp! Your organization is pending verification. You\'ll be notified once approved.',
                'is_read' => false,
            ]);

            DB::commit();

            // Fire the registered event (for email verification)
            event(new Registered($user));

            // Log the user in
            auth()->login($user);

            return redirect()->route('home')
                ->with('success', 'Registration successful! Your organization is pending verification. Please check your email to verify your account.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'Registration failed. Please try again.']);
        }
    }
}