<?php

// Defines the address of UpdateUserRequest.php
namespace App\Http\Requests\user;

// Import Enum rules for valid user roles
use App\Enums\UserRole;                        

use Illuminate\Foundation\Http\FormRequest;     // Predefined; the base class for custom, logic-rich form validation requests
use Illuminate\Validation\Rules\Enum;           // Provides methods for instantiating imported Enum classes
use Illuminate\Support\Str;                     // Provides string manipulation methods
use Illuminate\Support\Facades\Auth;            // Provides authentication methods to retrieve the currently logged-in user

class UpdateUserRequest extends FormRequest
{
    // Determines if the user is authorized to make this request
    public function authorize(): bool
    {
        return true; 
    }

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
        // Retrieve the user model being updated from the route parameter (e.g., /users/{user})
        $user = $this->route('user');
        
        // Checks if the authenticated user is updating their own profile
        $isSelf = Auth::id() === $user->id;

        return [
            'first_name'    => ['required', 'string', 'max:255'],                                   // Input is mandatory; max of 255 characters
            'middle_name'   => ['nullable', 'string', 'max:255'],                                   // Input is optional; max of 255 characters
            'last_name'     => ['required', 'string', 'max:255'],                                   // Input is mandatory; max of 255 characters
            'name_ext'      => ['nullable', 'string', 'max:10'],                                    // Input is optional; max of 10 characters
            'username'      => ['required', 'string', 'max:255', 'unique:users,username,' . $user->id], // Mandatory; check if input is not on the username column for the users table (ignores current user)
            'email'         => ['nullable', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id], // Optional; check if inputs is not on the email column for the users table (ignores current user)
            'contact'       => ['required', 'string', 'regex:/^(09|\+639)\d{9}$/'],                 // Mandatory; validates data if it follows the Philippine contact format (starts with 09 or +639)
            'role'          => [$isSelf ? 'nullable' : 'required', new Enum(UserRole::class)],      // Optional if updating self; Mandatory if admin updating someone else. Predefined inputs based on Enum rules in UserRole.php
            'department_id' => ['nullable', 'exists:departments,id'],                               // Optional; checks if department_id input is existing on the parent 'departments' table
        ];
    }
}