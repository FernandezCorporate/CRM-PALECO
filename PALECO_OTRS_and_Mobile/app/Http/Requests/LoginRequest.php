<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Set to true because anyone (unauthenticated users) can attempt to log in
        return true; 
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
            'role'     => ['required', new Enum(UserRole::class)],
        ];
    }
    
    /**
     * Optional: You can customize the error messages here if you want.
     */
    public function messages(): array
    {
        return [
            'role.required' => 'Please select your designated role to continue.',
        ];
    }
}