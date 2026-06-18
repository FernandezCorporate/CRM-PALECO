<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rules\Enum;

class AuthController extends Controller
{
    public function showLogin() { return view('auth.login'); }

    public function authenticate(Request $request)
    {
        $ipThrottleKey = 'login-ip:' . $request->ip();
        $accountThrottleKey = 'login-account:' . $request->input('username');

        // 1. IP Check (First line of defense)
        if (RateLimiter::tooManyAttempts($ipThrottleKey, 20)) {
            activity('security_alert')
                ->withProperties(['ip_address' => $request->ip()])
                ->log('account locked: too many attempts from IP');

            return back()->withErrors(['username' => 'Too many attempts from this connection. Wait 1 minute.']);
        }

        // 2. Account Check (Second line of defense)
        if (RateLimiter::tooManyAttempts($accountThrottleKey, 5)) {
            activity('security_alert')
                ->withProperties(['username' => $request->input('username')])
                ->log('account locked: too many attempts for user, wait 15 minutes');

            return back()->withErrors(['username' => 'This account is temporarily locked. Wait 15 minutes.']);
        }

        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
            'role' => ['required', new Enum(UserRole::class)],
        ]);

        // 3. Logic: Role restriction
        if (in_array($credentials['role'], [UserRole::FOREMAN->value, UserRole::FIELD_PERSONNEL->value])) {
            $this->logFailure($credentials['username'], 'Unauthorized role', $ipThrottleKey, $accountThrottleKey);
            return back()->withErrors(['role' => 'This role is not allowed.'])->onlyInput('username');
        }

        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']])) {
            
            // Logic: Deactivated check
            if (!Auth::user()->is_active) {
                $this->logFailure($credentials['username'], 'Account deactivated', $ipThrottleKey, $accountThrottleKey);
                Auth::logout();
                return back()->withErrors(['username' => 'Account deactivated.'])->onlyInput('username');
            }

            // Logic: Role mismatch
            if (Auth::user()->role->value !== $credentials['role']) {
                $this->logFailure($credentials['username'], 'Role mismatch', $ipThrottleKey, $accountThrottleKey);
                Auth::logout();
                return back()->withErrors(['role' => 'Role mismatch.'])->onlyInput('username');
            }

            // SUCCESS: Reset both throttlers
            RateLimiter::clear($ipThrottleKey);
            RateLimiter::clear($accountThrottleKey);
            
            $request->session()->regenerate();
            activity()->causedBy(Auth::user())->log('logged in');
            return redirect()->intended('/');
        }

        // FAILURE: Increment both throttlers
        $this->logFailure($credentials['username'], 'Invalid credentials', $ipThrottleKey, $accountThrottleKey);
        return back()->withErrors(['username' => 'Invalid credentials.'])->onlyInput('username');
    }

    private function logFailure($username, $reason, $ipKey, $accountKey)
    {
        // 60s lock for IP, 900s (15m) lock for Account
        RateLimiter::hit($ipKey, 60); 
        RateLimiter::hit($accountKey, 900);
        
        activity('failed_login')
            ->withProperties(['username' => $username, 'reason' => $reason])
            ->log('failed login attempt');
    }

    public function logout(Request $request)
    {
        if (Auth::check()) { activity()->causedBy(Auth::user())->log('logged out'); }
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}