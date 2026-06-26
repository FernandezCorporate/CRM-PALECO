<?php

// Defines the address of StoreUserRequest.php
namespace App\Http\Requests\user;

// Import Enum rules for valid user roles
use App\Enums\UserRole;                        

use Illuminate\Foundation\Http\FormRequest;     // Predefined; the base class for custom, logic-rich form validation requests
use Illuminate\Validation\Rules\Enum;           // Provides methods for instantiating imported Enum classes
use Illuminate\Support\Str;                     // Provides string manipulation methods

class StoreUserRequest extends FormRequest
{
    // Sanitizes HTTP request data
    protected function prepareForValidation(): void
    {
        $this->merge([                                                                      // Overwrite values in HTTP request body
            'first_name'  => $this->first_name ? Str::lower($this->first_name) : null,      // first_name value is lowercased before validation; returns null if not provided
            'middle_name' => $this->middle_name ? Str::lower($this->middle_name) : null,    // middle_name value is lowercased before validation; returns null if not provided
            'last_name'   => $this->last_name ? Str::lower($this->last_name) : null,        // last_name value is lowercased before validation; returns null if not provided
            'name_ext'    => $this->name_ext ? Str::lower($this->name_ext) : null,          // name_ext value is lowercased before validation; returns null if not provided
            'email'       => $this->email ? Str::lower($this->email) : null,                // email value is lowercased before validation; returns null if not provided
            'username'    => $this->username ? trim($this->username) : null,                // Removes whitespaces on username value
        ]);
    }

    // Defines the validation rules that apply to the request data
    public function rules(): array
    {
        return [
            'first_name'    => ['required', 'string', 'max:255'],                                   // Input is mandatory; max of 255 characters
            'middle_name'   => ['nullable', 'string', 'max:255'],                                   // Input is optional; max of 255 characters
            'last_name'     => ['required', 'string', 'max:255'],                                   // Input is mandatory; max of 255 characters
            'name_ext'      => ['nullable', 'string', 'max:10'],                                    // Input is optional; max of 10 characters
            'username'      => ['required', 'string', 'max:255', 'unique:users,username'],          // Mandatory; check if input is not on the username column for the users table
            'email'         => ['nullable', 'string', 'email', 'max:255', 'unique:users,email'],    // Optional; check if inputs is not on the email column for the users table
            'contact'       => ['required', 'string', 'regex:/^(09|\+639)\d{9}$/'],                 // Mandatory; validates data if it follows the Philippine contact format (starts with 09 or +639)
            'password'      => ['required', 'string', 'min:8', 'confirmed'],                        // Mandatory; requires at least 8 characters and matches the 'password_confirmation' input field on the blade view
            'role'          => ['required', new Enum(UserRole::class)],                             // Mandatory; valid inputs are predefined based on the Enum rules in UserRole.php
            'department_id' => ['nullable', 'exists:departments,id'],                               // Optional; checks if department_id input is existing on the parent 'departments' table
        ];
    }
}