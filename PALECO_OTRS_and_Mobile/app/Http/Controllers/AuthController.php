<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;

class AuthController extends Controller
{
    /**
     * Display the login page.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle login.
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
            'role'     => ['required', new Enum(UserRole::class)],
        ]);

        if (Auth::attempt([
            'username' => $credentials['username'],
            'password' => $credentials['password'],
            'role'     => $credentials['role'],
        ])) {
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        return back()
            ->withErrors([
                'username' => 'The provided credentials do not match our system records.',
            ])
            ->onlyInput('username');
    }

    /**
     * Logout user.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}