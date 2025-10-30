<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function store(Request $request)
    {
        // Validate the registration input
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        // Create a new user with password_hash column
        $user = User::create([
            'name' => 'Volunteer',
            'email' => $request->email,
            'password_hash' => Hash::make($request->password), // 👈 CHANGED HERE
        ]);

        // (Optional) automatically log in after registration
        auth()->login($user);

        // Redirect home with success message
        return redirect('/')->with('success', 'Registration successful!');
    }
}
