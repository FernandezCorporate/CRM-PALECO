<?php

// Define the address of LoginRequest.php
namespace App\Http\Requests\auth;

use App\Enums\UserRole;                         // Import Enum rules
use Illuminate\Foundation\Http\FormRequest;     // Predefined; the base class for custom, logic-rich form validation requests.
use Illuminate\Validation\Rules\Enum;           // Enables built-in validation to ensure inputs match defined Enum values.

class LoginRequest extends FormRequest
{

    // Sanitizes input by removing whitespaces from the username before validation
    protected function prepareForValidation(): void
    {
        if ($this->has('username')) {                   // 1. Checks if the user actually submitted a 'username' input
            $this->merge([                              // 2. Overwrites the 'username' value on the HTTP request with the trimmed version
                'username' => trim($this->username),    // 3. Removes whitespaces on the initial 'username' input
            ]);
        }
    }

    // Defines the validation rules that apply to the request data.
    public function rules(): array
    {
        return [
            'username' => ['required', 'string'],                   // Username is required and must be a string
            'password' => ['required', 'string'],                   // Password is required and must be a string
            'role'     => ['required', new Enum(UserRole::class)],  // Role input must match exactly one case defined in 'UserRole.php'
        ];
    }
}