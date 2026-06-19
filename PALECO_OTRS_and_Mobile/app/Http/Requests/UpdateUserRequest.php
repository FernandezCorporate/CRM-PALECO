<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        // Dynamically grab the user object passed into the route URL (e.g., /admin/users/{user})
        $user = $this->route('user');

        return [
            'first_name'  => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name'   => ['required', 'string', 'max:255'],
            'name_ext'    => ['nullable', 'string', 'max:10'],
            'username'    => ['required', 'string', 'max:255', 'unique:users,username,' . $user->id],
            'email'       => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password'    => ['nullable', 'string', 'min:8', 'confirmed'],
            'role'        => ['required', new Enum(UserRole::class)],
        ];
    }
}