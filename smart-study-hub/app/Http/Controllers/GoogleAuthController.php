<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Redirect to Google OAuth provider
     */
    public function redirectToGoogle()
    {
        try {
            // Ensure we have the required config
            $clientId = config('services.google.client_id');
            $clientSecret = config('services.google.client_secret');
            $redirectUri = config('services.google.redirect');
            
            if (!$clientId || !$clientSecret) {
                return redirect()->route('login')
                    ->with('error', 'Google OAuth is not properly configured. Please contact the administrator.');
            }
            
            return Socialite::driver('google')
                ->redirectUrl($redirectUri)
                ->redirect();
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Failed to initiate Google authentication: ' . $e->getMessage());
        }
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            $redirectUri = config('services.google.redirect');
            $googleUser = Socialite::driver('google')
                ->redirectUrl($redirectUri)
                ->user();
            
            // Check if user already exists
            $existingUser = User::where('email', $googleUser->getEmail())->first();
            
            if ($existingUser) {
                // User exists, log them in
                Auth::login($existingUser);
                
                // Redirect based on role
                return $this->redirectBasedOnRole($existingUser);
            } else {
                // Store Google user data in session for role selection
                session([
                    'google_user_name' => $googleUser->getName(),
                    'google_user_email' => $googleUser->getEmail(),
                    'google_user_avatar' => $googleUser->getAvatar(),
                    'google_user_id' => $googleUser->getId(),
                ]);
                
                // Redirect to role selection page
                return redirect()->route('auth.google.role-selection');
            }
            
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Something went wrong with Google authentication. Please try again.');
        }
    }

    /**
     * Show role selection page for new Google OAuth users
     */
    public function showRoleSelection()
    {
        // Check if we have Google user data in session
        if (!session('google_user_email')) {
            return redirect()->route('login')->with('error', 'Please sign in with Google first.');
        }
        
        return view('auth.role-selection');
    }

    /**
     * Handle role selection for new Google OAuth users
     */
    public function handleRoleSelection(Request $request)
    {
        $request->validate([
            'role' => 'required|in:student,teacher'
        ]);
        
        // Check if we have Google user data in session
        if (!session('google_user_email')) {
            return redirect()->route('login')->with('error', 'Session expired. Please sign in again.');
        }
        
        // Store selected role in session
        session(['google_user_role' => $request->role]);
        
        // Redirect to password creation page
        return redirect()->route('auth.google.password');
    }

    /**
     * Show password creation page
     */
    public function showPasswordCreation()
    {
        // Check if we have Google user data and role in session
        if (!session('google_user_email') || !session('google_user_role')) {
            return redirect()->route('login')->with('error', 'Session expired. Please sign in again.');
        }
        
        return view('auth.google-password');
    }

    /**
     * Handle password creation and complete registration
     */
    public function handlePasswordCreation(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        
        // Check if we have Google user data in session
        if (!session('google_user_email') || !session('google_user_role')) {
            return redirect()->route('login')->with('error', 'Session expired. Please sign in again.');
        }
        
        // Prepare user data
        $userData = [
            'name' => session('google_user_name'),
            'email' => session('google_user_email'),
            'password' => Hash::make($request->password), // Use manually entered password
            'email_verified_at' => now(), // Google emails are pre-verified
            'role' => session('google_user_role'), // User selected role from session
            'google_id' => session('google_user_id'),
            'profile_picture' => 'images/avatars/avatar-' . rand(1, 8) . '.svg', // Always use random default avatar
        ];
        
        // Generate student number for students
        if (session('google_user_role') === 'student') {
            $userData['student_number'] = User::generateStudentNumber();
        }
        
        // Create new user
        $newUser = User::create($userData);
        
        // Clear all session data
        session()->forget(['google_user_name', 'google_user_email', 'google_user_avatar', 'google_user_id', 'google_user_role']);
        
        Auth::login($newUser);
        
        // Redirect based on selected role
        return $this->redirectBasedOnRole($newUser)->with('success', 'Welcome to Smart Study Hub! Your account has been created.');
    }

    /**
     * Redirect user based on their role
     */
    private function redirectBasedOnRole($user)
    {
        switch ($user->role) {
            case 'teacher':
                return redirect()->route('teacher.dashboard');
            case 'student':
            default:
                return redirect()->route('student.dashboard');
        }
    }
}