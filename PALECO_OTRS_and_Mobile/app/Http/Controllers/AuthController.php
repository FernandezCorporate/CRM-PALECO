<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
            'role' => ['required', new Enum(UserRole::class)],
        ]);

        if (in_array($credentials['role'], [
            UserRole::FOREMAN->value,
            UserRole::FIELD_PERSONNEL->value,
        ])) {
            return back()->withErrors([
                'role' => 'This role is not allowed.'
            ])->onlyInput('username');
        }

        if (Auth::attempt([
            'username' => $credentials['username'],
            'password' => $credentials['password'],
        ])) {
            // 1. Check if the user account is deactivated
            if (!Auth::user()->is_active) {
                Auth::logout();

                return back()->withErrors([
                    'username' => 'Your account has been deactivated. Please contact an administrator.'
                ])->onlyInput('username');
            }

            // 2. Check for role mismatch (Your existing code)
            if (Auth::user()->role->value !== $credentials['role']) {
                Auth::logout();

                return back()->withErrors([
                    'role' => 'Role mismatch.'
                ])->onlyInput('username');
            }

            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'username' => 'Invalid credentials.',
        ])->onlyInput('username');
    }


    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}