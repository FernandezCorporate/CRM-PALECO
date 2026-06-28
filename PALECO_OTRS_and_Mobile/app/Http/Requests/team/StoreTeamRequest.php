<?php

// Define the address of StoreTeamRequest.php
namespace App\Http\Requests\team;

// Predefined; the base class for custom, logic-rich form validation requests.
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

use App\Enums\MemberRole;

class StoreTeamRequest extends FormRequest
{

    // Defines the validation rules that apply to the request data.
    public function rules(): array
    {
        return [
            'team_name' => [
                'required',                           // Input is mandatory
                'string',                             // Input must be a string
                'max:255',                            // Maximum of 255 characters
                Rule::unique('teams', 'team_name')->where(function ($query){
                    return $query->where('department_id', $this->input('department_id'));
                })
            ],
            'department_id' => [
                'required',                 // Input is mandatory
                'exists:departments,id'     // Checks if department_id input is existing on the parent 'departments' table
            ],
            'shift_start' => ['nullable', 'string'],    // Optional; expects a string value
            'shift_end' => ['nullable', 'string'],      // Optional; expects a string value

            'members' => ['nullable', 'array'],
            'members.*.user_id' => ['required', 'exists:users,id', 'distinct'],
            'members.*.role' => ['required', Rule::enum(MemberRole::class)],
        ];
    }

    public function after(): array
    {
        return [
            function ($validator) {
                $members = $this->input('members', []);

                $leaderCount = collect($members)
                    ->where('role', MemberRole::LEADER->value)
                    ->count();

                if ($leaderCount > 1) {
                    $validator->errors()->add('members', 'A team is strictly limited to one Team Leader');
                }
            }
        ];
    }

    public function messages(): array
    {
        return [
            // Team Name constraints
            'team_name.required' => 'A team name is required to identify the unit.',
            'team_name.unique'   => 'A team with this name already exists in this department.',
            
            // Department constraints
            'department_id.required' => 'Please select the parent department for this team.',
            'department_id.exists'   => 'The selected department is invalid.',
            
            // Member/Roster constraints
            'members.*.user_id.required' => 'Please select a personnel for all rows.',
            'members.*.user_id.distinct' => 'You have selected the same personnel more than once.',
            'members.*.role.required'    => 'Please assign a role to every team member.',
        ];
    }
}
