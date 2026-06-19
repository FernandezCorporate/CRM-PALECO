<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Support\Str;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    // 💡 Sanitization: Clean all strings before checking if they are unique
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

    public function rules(): array
    {
        return [
            'first_name'  => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name'   => ['required', 'string', 'max:255'],
            'name_ext'    => ['nullable', 'string', 'max:10'],
            'username'    => ['required', 'string', 'max:255', 'unique:users,username'],
            'email'       => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'    => ['required', 'string', 'min:8', 'confirmed'],
            'role'        => ['required', new Enum(UserRole::class)],
        ];
    }
}