<?php

namespace App\Http\Requests\team;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTeamRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $team = $this->route('team');

        return [
            'team_name' => ['required', 'string', 'max:255', 'unique:teams,team_name,' . $team->id],
            'department_id' => ['required', 'exists:departments,id'],
            'shift_start' => ['nullable', 'string'],
            'shift_end' => ['nullable', 'string'],
        ];
    }
}
