<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rules\Enum;

class AuthController extends Controller
{
    /**
     * Show the login page.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle authentication attempt.
     */
    public function authenticate(Request $request)
    {
        $throttleKey = 'login:' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            return back()->withErrors(['username' => 'Too many attempts. Please wait.']);
        }

        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
            'role' => ['required', new Enum(UserRole::class)],
        ]);

        // 1. Role Restriction Failure
        if (in_array($credentials['role'], [UserRole::FOREMAN->value, UserRole::FIELD_PERSONNEL->value])) {
            RateLimiter::hit($throttleKey, 60);
            activity('failed_login')->withProperties(['username' => $credentials['username'], 'reason' => 'Unauthorized role'])->log('failed login attempt');
            return back()->withErrors(['role' => 'This role is not allowed.'])->onlyInput('username');
        }

        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']])) {
            
            // 2. Account Deactivated Failure
            if (!Auth::user()->is_active) {
                RateLimiter::hit($throttleKey, 60);
                activity('failed_login')->withProperties(['username' => $credentials['username'], 'reason' => 'Account deactivated'])->log('failed login attempt');
                Auth::logout();
                return back()->withErrors(['username' => 'Your account has been deactivated.'])->onlyInput('username');
            }

            // 3. Role Mismatch Failure
            if (Auth::user()->role->value !== $credentials['role']) {
                RateLimiter::hit($throttleKey, 60);
                activity('failed_login')->withProperties(['username' => $credentials['username'], 'reason' => 'Role mismatch'])->log('failed login attempt');
                Auth::logout();
                return back()->withErrors(['role' => 'Role mismatch.'])->onlyInput('username');
            }

            // Success
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();
            activity()->causedBy(Auth::user())->log('logged in');
            return redirect()->intended('/');
        }

        // 4. Invalid Credentials Failure
        RateLimiter::hit($throttleKey, 60);
        activity('failed_login')->withProperties(['username' => $credentials['username'], 'reason' => 'Invalid credentials'])->log('failed login attempt');

        return back()->withErrors(['username' => 'Invalid credentials.'])->onlyInput('username');
    }

    /**
     * Handle logout.
     */
    public function logout(Request $request)
    {
        if (Auth::check()) {
            activity()->causedBy(Auth::user())->log('logged out');
        }
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}