<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'first_name'  => $this->first_name ? Str::lower($this->first_name) : null,
            'middle_name' => $this->middle_name ? Str::lower($this->middle_name) : null,
            'last_name'   => $this->last_name ? Str::lower($this->last_name) : null,
            'name_ext'    => $this->name_ext ? Str::lower($this->name_ext) : null,
            'email'       => $this->email ? Str::lower($this->email) : null,
            'username'    => $this->username ? trim($this->username) : null,
        ]);
    }

    public function rules(): array
    {
        $user = $this->route('user');
        
        // 💡 Check if the currently logged-in admin is editing their own account
        $isSelf = Auth::id() === $user->id;

        return [
            'first_name'  => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name'   => ['required', 'string', 'max:255'],
            'name_ext'    => ['nullable', 'string', 'max:10'],
            'username'    => ['required', 'string', 'max:255', 'unique:users,username,' . $user->id],
            'email'       => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password'    => ['nullable', 'string', 'min:8', 'confirmed'],
            
            // 💡 If editing themselves, role is nullable. Otherwise, it is required.
            'role'        => [$isSelf ? 'nullable' : 'required', new Enum(UserRole::class)],
            
            'department_id' => ['nullable', 'exists:departments,id'],
            'shift_start'   => ['nullable', 'string'],
            'shift_end'     => ['nullable', 'string'],
        ];
    }
}