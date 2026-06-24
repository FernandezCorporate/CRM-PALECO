<?php

// Define the address of LoginRequest.php
namespace App\Http\Requests\auth;

use App\Enums\UserRole;     // Import Enum rules
use Illuminate\Foundation\Http\FormRequest;     // Predefined; the base class for custom, logic-rich form validation requests.
use Illuminate\Validation\Rules\Enum;       // Enables built-in validation to ensure inputs match defined Enum values.

class LoginRequest extends FormRequest
{

    // Determines if the user is authorized to make this request.
    public function authorize(): bool
    {
        return true; 
    }

    // Sanitizes input by removing whitespaces from the username before validation.
    protected function prepareForValidation(): void
    {
        if ($this->has('username')) {
            $this->merge([
                'username' => trim($this->username),
            ]);
        }
    }

    // Defines the validation rules that apply to the request data.
    public function rules(): array
    {
        return [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
            'role'     => ['required', new Enum(UserRole::class)],  // Role input must match exactly one case defined in 'UserRole.php'
        ];
    }
}