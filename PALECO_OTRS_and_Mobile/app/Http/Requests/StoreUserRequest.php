<?php

namespace App\Http\Requests;

use App\Enums\UserRole;     // Import Enum rules
use Illuminate\Foundation\Http\FormRequest;     // Predefined; the base class for custom, logic-rich form validation requests.
use Illuminate\Validation\Rules\Enum;       // Enables built-in validation to ensure inputs match defined Enum values.
use Illuminate\Support\Str;     // Provides string manipulation functions

class StoreUserRequest extends FormRequest
{

    // Determines if user is authorized to make this request.
    public function authorize(): bool
    {
        return true; 
    }

    // Cleans user inputs before saving into database
    // All fields, except username, are lowercased before being stored in the database for data integrity
    protected function prepareForValidation(): void
    {
        $this->merge([
            'first_name'  => $this->first_name ? Str::lower($this->first_name) : null,
            'middle_name' => $this->middle_name ? Str::lower($this->middle_name) : null,
            'last_name'   => $this->last_name ? Str::lower($this->last_name) : null,
            'name_ext'    => $this->name_ext ? Str::lower($this->name_ext) : null,
            'email'       => $this->email ? Str::lower($this->email) : null,
            'username'    => $this->username ? trim($this->username) : null, // Preserve casing, just trim
        ]);
    }

    // Defines the validation rules that apply to the request data.
    public function rules(): array
    {
        return [
            'first_name'  => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name'   => ['required', 'string', 'max:255'],
            'name_ext'    => ['nullable', 'string', 'max:10'],
            'username'    => ['required', 'string', 'max:255', 'unique:users,username'],
            'email'       => ['nullable', 'string', 'email', 'max:255', 'unique:users,email'],
            'contact'     => ['required', 'string', 'regex:/^(09|\+639)\d{9}$/'],
            'password'    => ['required', 'string', 'min:8', 'confirmed'],
            'role'        => ['required', new Enum(UserRole::class)],   // Role input must match exactly one case defined in 'UserRole.php'
            'department_id' => ['nullable', 'exists:departments,id'],
            'shift_start'   => ['nullable', 'date_format:H:i'],
            'shift_end'     => ['nullable', 'date_format:H:i'],
        ];
    }
}