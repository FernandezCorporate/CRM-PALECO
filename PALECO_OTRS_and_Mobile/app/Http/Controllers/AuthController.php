<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Enums\LogName;
use App\Enums\LogDescription;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{
    public function showLogin() { return view('auth.login'); }

    public function authenticate(LoginRequest $request)
    {
        $ipThrottleKey = 'login-ip:' . $request->ip();
        $accountThrottleKey = 'login-account:' . $request->input('username');

        // 1. IP Check
        if (RateLimiter::tooManyAttempts($ipThrottleKey, 20)) {
            activity(LogName::SECURITY_ALERT->value)
                ->withProperties(['ip_address' => $request->ip()])
                ->log(LogDescription::IP_LOCKED->value);

            return back()->withErrors(['username' => 'Too many attempts from this connection. Wait 1 minute.']);
        }

        // 2. Account Check
        if (RateLimiter::tooManyAttempts($accountThrottleKey, 5)) {
            activity(LogName::SECURITY_ALERT->value)
                ->withProperties(['username' => $request->input('username')])
                ->log(LogDescription::USER_LOCKED->value);

            return back()->withErrors(['username' => 'This account is temporarily locked. Wait 15 minutes.']);
        }

        // 💡 Data is cleanly validated and trimmed by LoginRequest
        $credentials = $request->validated();

        // 3. Role restriction
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

            // SUCCESS
            RateLimiter::clear($ipThrottleKey);
            RateLimiter::clear($accountThrottleKey);
            
            $request->session()->regenerate();
            
            activity(LogName::SYSTEM_DEFAULT->value)
                ->causedBy(Auth::user())
                ->log(LogDescription::LOGGED_IN->value);
                
            return redirect()->intended('/');
        }

        // FAILURE
        $this->logFailure($credentials['username'], 'Invalid credentials', $ipThrottleKey, $accountThrottleKey);
        return back()->withErrors(['username' => 'Invalid credentials.'])->onlyInput('username');
    }

    private function logFailure($username, $reason, $ipKey, $accountKey)
    {
        RateLimiter::hit($ipKey, 60); 
        RateLimiter::hit($accountKey, 900);
        
        activity(LogName::FAILED_LOGIN->value)
            ->withProperties(['username' => $username, 'reason' => $reason])
            ->log(LogDescription::LOGIN_FAILED->value);
    }

    public function logout(Request $request)
    {
        if (Auth::check()) { 
            activity(LogName::SYSTEM_DEFAULT->value)
                ->causedBy(Auth::user())
                ->log(LogDescription::LOGGED_OUT->value); 
        }
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}