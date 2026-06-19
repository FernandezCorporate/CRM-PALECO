<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    // 💡 Sanitization: Strip accidental spaces from the username
    protected function prepareForValidation(): void
    {
        if ($this->has('username')) {
            $this->merge([
                'username' => trim($this->username),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
            'role'     => ['required', new Enum(UserRole::class)],
        ];
    }
}