<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
// 1. Import your new LoginRequest
use App\Http\Requests\LoginRequest; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{
    public function showLogin() { return view('auth.login'); }

    // 2. Type-hint LoginRequest instead of the standard Request
    public function authenticate(LoginRequest $request) 
    {
        $ipThrottleKey = 'login-ip:' . $request->ip();
        $accountThrottleKey = 'login-account:' . $request->input('username');

        if (RateLimiter::tooManyAttempts($ipThrottleKey, 20)) {
            activity('security_alert')
                ->withProperties(['ip_address' => $request->ip()])
                ->log('account locked: too many attempts from IP');

            return back()->withErrors(['username' => 'Too many attempts from this connection. Wait 1 minute.']);
        }

        if (RateLimiter::tooManyAttempts($accountThrottleKey, 5)) {
            activity('security_alert')
                ->withProperties(['username' => $request->input('username')])
                ->log('account locked: too many attempts for user, wait 15 minutes');

            return back()->withErrors(['username' => 'This account is temporarily locked. Wait 15 minutes.']);
        }

        // 3. Retrieve the already-validated data cleanly
        $credentials = $request->validated();

        if (in_array($credentials['role'], [UserRole::FOREMAN->value, UserRole::FIELD_PERSONNEL->value])) {
            $this->logFailure($credentials['username'], 'Unauthorized role', $ipThrottleKey, $accountThrottleKey);
            return back()->withErrors(['role' => 'This role is not allowed.'])->onlyInput('username');
        }

        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']])) {
            
            if (!Auth::user()->is_active) {
                $this->logFailure($credentials['username'], 'Account deactivated', $ipThrottleKey, $accountThrottleKey);
                Auth::logout();
                return back()->withErrors(['username' => 'Account deactivated.'])->onlyInput('username');
            }

            if (Auth::user()->role->value !== $credentials['role']) {
                $this->logFailure($credentials['username'], 'Role mismatch', $ipThrottleKey, $accountThrottleKey);
                Auth::logout();
                return back()->withErrors(['role' => 'Role mismatch.'])->onlyInput('username');
            }

            RateLimiter::clear($ipThrottleKey);
            RateLimiter::clear($accountThrottleKey);
            
            $request->session()->regenerate();
            activity()->causedBy(Auth::user())->log('logged in');
            return redirect()->intended('/');
        }

        $this->logFailure($credentials['username'], 'Invalid credentials', $ipThrottleKey, $accountThrottleKey);
        return back()->withErrors(['username' => 'Invalid credentials.'])->onlyInput('username');
    }

    private function logFailure($username, $reason, $ipKey, $accountKey)
    {
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