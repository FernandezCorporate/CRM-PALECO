<?php

namespace App\Http\Controllers;

// Import Enum rules
use App\Enums\UserRole;
use App\Enums\LogName;
use App\Enums\LogDescription;

use App\Http\Requests\LoginRequest;     // Import Requests (Cleans and validates user inputs)
use Illuminate\Http\Request;    // Handles incoming HTTP requests
use Illuminate\Support\Facades\Auth;    // Provides authentication functions
use Illuminate\Support\Facades\RateLimiter;     // Provides rate limiting functions

class AuthController extends Controller
{
    // Redirects users to the login page upon visiting the system
    public function showLogin() { return view('auth.login'); }

    // Handles authentication of user credentials
    public function authenticate(LoginRequest $request)
    {
        // Defines throttle keys to track login requests made by an IP Address and username
        $ipThrottleKey = 'login-ip:' . $request->ip();
        $accountThrottleKey = 'login-account:' . $request->input('username');

        // Logs an IP Address into the 'activitiy_logs' table after a failed login attempt
        if (RateLimiter::tooManyAttempts($ipThrottleKey, 20)) {
            // Calculate exactly how many seconds until the IP lockout expires
            $seconds = RateLimiter::availableIn($ipThrottleKey);
            
            activity(LogName::SECURITY_ALERT->value)
                ->withProperties(['ip_address' => $request->ip()])
                ->log(LogDescription::IP_LOCKED->value);

            return back()->withErrors(['username' => "Too many attempts from this connection. Please try again in {$seconds} seconds."]);
        }

        // Logs a username into the 'activitiy_logs' table after a failed login attempt
        if (RateLimiter::tooManyAttempts($accountThrottleKey, 5)) {
            // Calculate exactly how many minutes until the account lockout expires
            $seconds = RateLimiter::availableIn($accountThrottleKey);
            $minutes = ceil($seconds / 60);
            
            activity(LogName::SECURITY_ALERT->value)
                ->withProperties(['username' => $request->input('username')])
                ->log(LogDescription::USER_LOCKED->value);

            return back()->withErrors(['username' => "This account is temporarily locked. Please try again in {$minutes} minutes."]);
        }

        // Data is cleanly validated and trimmed by 'LoginRequest.php'
        $credentials = $request->validated();

        // Main auth functions

        // 1. Authorized role check
        // Admin and CWD are only allowed for this specific auth controller since they are defined users for the web system
        if (in_array($credentials['role'], [UserRole::FOREMAN->value, UserRole::FIELD_PERSONNEL->value])) {
            $this->logFailure($credentials['username'], 'Unauthorized role', $ipThrottleKey, $accountThrottleKey);
            return back()->withErrors(['role' => 'This role is not allowed.'])->onlyInput('username');
        }

        // 2. Username and Password check
        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']])) {
            
            // 3. Account status check
            if (!Auth::user()->is_active) {
                $this->logFailure($credentials['username'], 'Account deactivated', $ipThrottleKey, $accountThrottleKey);
                Auth::logout();
                return back()->withErrors(['username' => 'Account deactivated.'])->onlyInput('username');
            }

            // 4. Role and credentials match check  
            if (Auth::user()->role->value !== $credentials['role']) {
                $this->logFailure($credentials['username'], 'Role mismatch', $ipThrottleKey, $accountThrottleKey);
                Auth::logout();
                return back()->withErrors(['role' => 'Role mismatch.'])->onlyInput('username');
            }

            // 5. Reset rate limiter count for IP and username if successful login
            RateLimiter::clear($ipThrottleKey);
            RateLimiter::clear($accountThrottleKey);
            
            // 6. Generate new session ID for authorized user 
            $request->session()->regenerate();
            
            // 7. Record successful login
            activity(LogName::SYSTEM_DEFAULT->value)
                ->causedBy(Auth::user())
                ->log(LogDescription::LOGGED_IN->value);
                
            return redirect()->intended('/');
        }

        // Failed login
        // Calls 'logFailure()' function
        $this->logFailure($credentials['username'], 'Invalid credentials', $ipThrottleKey, $accountThrottleKey);
        return back()->withErrors(['username' => 'Invalid credentials.'])->onlyInput('username');
    }

    // Handles failed login attempts
    private function logFailure($username, $reason, $ipKey, $accountKey)
    {
        // Increase attempt count for the failed IP and username
        // IP counter lives for 1 minute; Username counter lives for 15 minutes
        RateLimiter::hit($ipKey, 60); 
        RateLimiter::hit($accountKey, 900);
        
        // Record failed login attempt to 'activity_logs'
        activity(LogName::FAILED_LOGIN->value)
            ->withProperties(['username' => $username, 'reason' => $reason])
            ->log(LogDescription::LOGIN_FAILED->value);
    }

    // Handles logout requests
    public function logout(Request $request)
    {
        
        // Record logout action to 'activity_logs'
        if (Auth::check()) { 
            activity(LogName::SYSTEM_DEFAULT->value)
                ->causedBy(Auth::user())
                ->log(LogDescription::LOGGED_OUT->value); 
        }
        
        // Executes built-in logout function by Auth
        // Deletes auth session ID and replaces it with a guest session ID
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirects logged out users back to login page
        return redirect('/login');
    }
}