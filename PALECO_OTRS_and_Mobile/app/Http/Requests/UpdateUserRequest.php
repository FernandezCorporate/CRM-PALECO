<?php

namespace App\Http\Requests;

use App\Enums\UserRole;             // Imports Enum roles
use Illuminate\Foundation\Http\FormRequest;   // Provides the base class for custom, logic-rich form validation requests
use Illuminate\Validation\Rules\Enum;      // Enables built-in validation to ensure inputs match defined Enum values
use Illuminate\Support\Str;             // Provides string manipulation functions

class UpdateUserRequest extends FormRequest
{
    // Determines if the user is authorized to make this request.
    public function authorize(): bool
    {
        return true; 
    }

    // Sanitizes user inputs before validation to ensure consistent data storage.
    // All string fields are lowercased for uniformity, while usernames are trimmed.
    protected function prepareForValidation(): void
    {
        $this->merge([
            'first_name'  => $this->first_name ? Str::lower($this->first_name) : null,
            'middle_name' => $this->middle_name ? Str::lower($this->middle_name) : null,
            'last_name'   => $this->last_name ? Str::lower($this->last_name) : null,
            'name_ext'    => $this->name_ext ? Str::lower($this->name_ext) : null,
            'email'       => $this->email ? Str::lower($this->email) : null,
            'username'    => $this->username ? trim($this->username) : null, // Preserve casing, just trim.
        ]);
    }

    // Defines the validation rules that apply to the request data.
    public function rules(): array
    {
        // Fetch the current user instance to ignore their own unique ID during uniqueness checks.
        $user = $this->route('user');

        return [
            'first_name'  => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name'   => ['required', 'string', 'max:255'],
            'name_ext'    => ['nullable', 'string', 'max:10'],
            'username'    => ['required', 'string', 'max:255', 'unique:users,username,' . $user->id], // Ensures username uniqueness, ignoring current record.
            'email'       => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id], // Ensures email uniqueness, ignoring current record.
            'password'    => ['nullable', 'string', 'min:8', 'confirmed'], // Password is optional during updates; must match confirmation if provided.
            'role'        => ['required', new Enum(UserRole::class)],   // Role input must match exactly one case defined in 'UserRole.php'.
            'department_id' => ['required', 'exists:departments,id'],
            'shift_start'   => ['nullable', 'string'],
            'shift_end'     => ['nullable', 'string'],
        ];
    }
}