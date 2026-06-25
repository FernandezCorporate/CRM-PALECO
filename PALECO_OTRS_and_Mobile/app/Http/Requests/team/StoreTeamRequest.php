<?php

// Define the address of StoreTeamRequest.php
namespace App\Http\Requests\team;

// Predefined; the base class for custom, logic-rich form validation requests.
use Illuminate\Foundation\Http\FormRequest;

class StoreTeamRequest extends FormRequest
{

    // Defines the validation rules that apply to the request data.
    public function rules(): array
    {
        return [
            'team_name' => [
                'required',                 // Input is mandatory
                'string',                   // Input must be a string
                'max:255',                  // Maximum of 255 characters
                'unique:teams,team_name'    // Check if input is not on the team_name column for the teams table
            ],
            'department_id' => [
                'required',                 // Input is mandatory
                'exists:departments,id'     // Checks if department_id input is existing on the parent 'departments' table
            ],
            'shift_start' => ['nullable', 'string'],
            'shift_end' => ['nullable', 'string'],
        ];
    }
}
