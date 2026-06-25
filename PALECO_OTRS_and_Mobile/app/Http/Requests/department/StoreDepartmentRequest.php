<?php

// Define the address of StoreDepartmentRequest.php
namespace App\Http\Requests\department;

// Predefined; the base class for custom, logic-rich form validation requests.
use Illuminate\Foundation\Http\FormRequest;

class StoreDepartmentRequest extends FormRequest
{

    // Defines the validation rules that apply to the request data.
    public function rules(): array
    {
        return [
            'dept_name' => [
                'required',                     // Input is mandatory
                'string',                       // Input must be a string
                'max:255',                      // Maximum of 255 characters
                'unique:departments,dept_name'  // Checks if input is not on the dept_name column for departments table
            ],
            'dept_description' => ['
                nullable',                      // Input is optional
                'string',                       // Input must be a string, if provided
                'max:1000'                      // Maximum of 1000 characters; expects description content
            ],
        ];
    }
}