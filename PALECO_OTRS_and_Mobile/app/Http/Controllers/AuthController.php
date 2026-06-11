<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Display the custom UI Sign-in form layout.
     * Maps to: GET /login
     */
    public function showLogin()
    {
        return view('auth.login'); 
    }

    /**
     * Handle an incoming authentication/sign-in request.
     * Maps to: POST /login
     */
    public function authenticate(Request $request)
    {
        // 1. Validate the incoming form inputs
        $credentials = $request->validate([
            'username' => ['required', 'string'], // Validates username text 
            'password' => ['required', 'string'], // Validates raw password entry
            'role'     => ['required', 'string'], // Validates that a UI role card selection was sent
        ]);

        // 2. Attempt to look up credentials against your MySQL unique username column
        $loginAttempt = Auth::attempt([
            'username' => $credentials['username'], 
            'password' => $credentials['password']
        ]);

        if ($loginAttempt) {
            
            // 3. SECURITY CHECK: Verify if their profile role matches what they selected on the UI cards
            // If they clicked the 'Admin' card but their database row says 'cwd', block them.
            if (Auth::user()->role !== $credentials['role']) {
                Auth::logout();
                
                return back()->withErrors([
                    'role' => 'Access Denied: Your account role does not match this selected portal gate.'
                ])->onlyInput('username');
            }

            // 4. Secure the session ID tokens against fixation attacks
            $request->session()->regenerate();

            // 5. Redirect the authorized user straight to the protected root dashboard
            return redirect()->intended('/');
        }

        // 6. Return error feedback if credential parameters fail database lookup
        return back()->withErrors([
            'username' => 'The provided credentials do not match our system records.',
        ])->onlyInput('username'); // Retains their typed username in the input text box
    }

    /**
     * Log the employee session out of the portal framework.
     * Maps to: POST /logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Flush all active session data arrays from the database storage
        $request->session()->invalidate();

        // Regenerate the CSRF security tokens
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
