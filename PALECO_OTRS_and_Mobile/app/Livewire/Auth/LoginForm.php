<?php

// app/Livewire/Auth/LoginForm.php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class LoginForm extends Component
{
    // Bound to the username and password inputs via wire:model
    public string $username = '';
    public string $password = '';

    // Tracks which role card is currently selected — defaults to 'admin'
    // Replaces the hidden <input id="role-input"> and the vanilla JS selectRole() function
    public string $role = 'admin';

    // Validation rules — mirrors AuthController::authenticate() validate()
    protected array $rules = [
        'username' => ['required', 'string'],
        'password' => ['required', 'string'],
        'role'     => ['required', 'string'],
    ];

    protected array $messages = [
        'username.required' => 'Please enter your username.',
        'password.required' => 'Please enter your password.',
    ];

    // Called by wire:click="selectRole('admin')" on the role cards
    // Replaces the entire vanilla JS selectRole() function and DOM manipulation
    public function selectRole(string $role): void
    {
        $this->role = $role;
    }

    // Called by wire:submit="login" on the form
    // Mirrors AuthController::authenticate() exactly
    public function login(): void
    {
        // 1. Validate inputs
        $this->validate();

        // 2. Attempt auth against the username column
        $loginAttempt = Auth::attempt([
            'username' => strip_tags(trim($this->username)),
            'password' => $this->password,
        ]);

        if ($loginAttempt) {

            // 3. Role mismatch check — same logic as the controller
            if (Auth::user()->role !== $this->role) {
                Auth::logout();

                $this->addError('role', 'Access Denied: Your account role does not match this selected portal gate.');
                return;
            }

            // 4. Regenerate session ID
            request()->session()->regenerate();

            // 5. Redirect to dashboard
            $this->redirect(route('dashboard'), navigate: true);
            return;
        }

        // 6. Wrong credentials error — shown above the form
        $this->addError('username', 'The provided credentials do not match our system records.');
    }

    public function render()
    {
        return view('livewire.auth.login-form');
    }
}
